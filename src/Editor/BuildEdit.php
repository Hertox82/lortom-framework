<?php

namespace LTFramework\Editor;

use LTFramework\Exceptions\LTHttpException;
use Schema;

class BuildEdit {

    protected $class = null;
    protected $objId = 0;
    protected $Obj = null;
    protected $input = [];
    protected $type = null;
    protected $blocks = [];


    public function init(array $data) {
        $params = [
            'class'   => null,
            'objId' => null,
            'input' => null
        ];

        foreach($data as $key => $value) {

            $this->$key = $value;
        }

        $class = $this->class;
        if ($class != null) {
            if($this->objId != 0) {
                $this->Obj = $class::find($this->objId);
                if(multisite()->hasModelReadable($class)) {
                    if(!Schema::hasColumn($this->Obj->getTable(),'site')) {
                        // $this->Obj = null;
                        throw new LTHttpException(response()->json(['error' => "{$this->Obj->getTable()} not have site column"],404));
                    } else {
                        if ($this->Obj->site !== multisite()->getIdSite()) {
                            throw new LTHttpException(response()->json(['error' => 'The Owner of this Object is Other Site'],404));
                        }
                    }
                }
                if($this->Obj) {
                    $this->type = 'update';
                } else {
                    throw new LTHttpException(response()->json(['error' => 'file not found'],404));
                }
            } else {
                $this->Obj = new $class;
                $this->type = 'store';
            }
        }

        return $this;
    }

    /**
     * This function add abBlock or abField
     */
    public function addCp($type,$abstract,$params){

        if($type == 'bl')
        {
            return $this->addBlock($abstract,$params);
        }
        elseif( $type == 'fl')
        {
            if(!isset($params['db_table'])) {
                $params['db_table'] = $this->Obj->getTable();
            }
            return $this->addField(app("lt.{$abstract}")->init($params));
        }
        elseif( $type == 'act') {
            return $this->addAction($abstract, $params);
        }
    }



    public function addBlock($type, $block) {
        $params = [
            'data' => [
                'label' => '',
                'icons' => ''
            ],
            'type'  => '',
            'child' => [],
            'initialized' => true,
        ];

        $data = [];
        $Keys = array_keys($block);
        foreach($Keys as $key) {
            if($key != 'fields' and $key != 'initialized') {
                $data['data'][$key] = $block[$key];
            }
        }
       // $data['data']['label'] = $block['label'];
       // $data['data']['icons'] = $block['icons'];
        $data['type'] = $type;
        $data['child'] = [];

        
        if($type === 'modal') {
            $data['child'] = $block['fields'];
        }

        $data['initialized'] = (isset($block['initialized'])) ? $block['initialized'] : true;


        $this->blocks[] = $data;
    
        return $this;
    }

    public function addField(AbstractField $field) {
        $activeBlock = count($this->blocks) - 1;
        $params = [
            'data'  => [
                'label' => '',
                'placeholder' => '',
                'name'  => '',
                'data'  => '',
                'isEdit'  => false,
                'db_callable' => '',
                'db_table' => '',  // []
                'default'   => ''

            ],
            'type' => ''
        ];

        if($this->Obj) {
            if($this->Obj->exists) {
                $fieldName = $field->getProperty('name');
                if($field->db_table === $this->Obj->getTable()){
                    if(key_exists($fieldName, $this->Obj->getAttributes())) {
                        $field->data = $this->formatting($field->formatting,[$this->Obj,$fieldName]);
                    }
                    else
                        $field->data = $field->default;
                } else {
                    if(is_null($field->db_callable)) {
                        $field->data = $this->formatting($field->formatting,[$this->Obj,'getOtherTableCol'],[$field->db_table, $field->name]);
                    }
                    else { 
                        $field->data = $this->formatting($field->formatting, [$this->Obj,'getOtherTableCol'],[$field->db_table, $field->name, $field->db_callable]);
                    }
                }
            }
        }

        $this->blocks[$activeBlock]['child'][] = $field->serialize();

        return $this;

    }

    public function getObj() {
        return $this->Obj;
    }

    protected function formatting($closure, $caller, $parameter = []) {
        if(!is_null($closure) and ($closure instanceof \Closure)) {
            if(count($parameter) === 0) {
                list($obj,$param) = $caller;
                return $closure($obj->$param);
            } 
            return $closure(call_user_func_array($caller,$parameter));
            
        } else {
            if(count($parameter) === 0) {
                list($obj,$param) = $caller;
                return $obj->$param;
            } 
            return call_user_func_array($caller,$parameter);
        }
    }

    public function addAction($abstract, $params){
        $activeBlock = count($this->blocks) - 1;
        $data = [
            'type'  => $abstract
        ];
        $data = array_merge($data, $params);

        $this->blocks[$activeBlock]['actions'][] = $data;
        return $this;
    }

    public function extract() {
        return array_merge(['id' => $this->objId],['blocks' => $this->blocks]);
        // return $this->blocks;
    }
}
