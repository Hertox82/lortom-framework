<?php

namespace LTFramework\Services\Editor;

use Schema;
use LTFramework\Exceptions\LTHttpException;

class TabList {
    
    protected $columns = [];
    protected $Obj;
    protected $class;
    protected $actions = [];
    protected $tabs = [];
    protected $where = [];
    protected $orderBy = '';

    public function init(array $data = []) {
        $this->class = $data['class'];
        $this->orderBy = (isset($data['orderBy'])) ? $data['orderBy'] : '';
        if(multisite()->hasModelReadable($this->class)) {
            $class = $this->class;
            $obj = new $class();
            if (Schema::hasColumn($obj->getTable(),'site')) {
                $this->addWhere([
                    'site', multisite()->getIdSite()
                ]);
            } else {
                throw new LTHttpException(response()->json(['error' => "{$obj->getTable()} not have site column"],404));
            }
        }
        
        return $this;
    }
    
    public function addColumns(array $data = []){
        $params = [
            'field' => '', 
            'label' => '',
            'type'  => 'text', //text, id, checkbox, date, preset
            'join'  => [
                'relationship' => 'function_to_call',
                'field'        => 'fieldToShow'
             ]
        ];

        $this->columns[] = $data;

        return $this;
    }

    public function addAction(array $data = []) {
        $params = [
            'data'  => [
                'routing'       => '',
                'styleIcon'     => '',
                'styleButton'   => '',
                'label'         => '',
            ],
            'type'  => 'routing'    // routing, event
        ];

        $this->actions[] = $data;

        return $this;
    }

    public function addWhere(array $data = []) {
        $this->where[] = $data;

        return $this;
    }

    public function addTab(array $data = []) {
        $params = [
            'label'     => '',
            'active'    => false,
            'routing'   => null 
        ];

        $this->tabs[] = $data;

        return $this;
    }

    public function extract() {
        $listTableHeader = $this->extractTableHeader();
        return [
            'tableHeader' => $listTableHeader,
            'actions'     => $this->actions,
            'data'        => $this->class::getAllItems($this->columns,$this->where,$this->orderBy),
            'tabs'        => $this->tabs
        ];
    }


    protected function extractTableHeader() {
        $response = [];
        $response['fields'][] = ['label' => '', 'value' => '', 'type' => 'checkbox'];

        foreach($this->columns as $col) {
            $col['value'] = '';
            unset($col['field']);
            if($col['type'] === 'preset' or $col['type'] === 'date') 
                $col['type'] = 'text';
            $response['fields'][] = $col;
        }

        return $response;
    }
}