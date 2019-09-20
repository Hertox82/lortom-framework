<?php 

namespace LTFramework\Model;

use Illuminate\Database\Eloquent\Model;
use DB;

abstract class LTModel extends Model {

    public function gVal($field, $extra = array()) {
        return [];
    }

    public static function gValS($field, $extra = array()) {
        $className = get_called_class();
        $Obj = new $className();
        $list = $Obj->gVal($field, $extra);

        return $list;
    }

    public static function gValBack($field, $id) {
        $className = get_called_class();
        $Obj = new $className();
        $list = $Obj->gVal($field);


        foreach($list as $item) {
            if($item['id'] == $id) {
                return $item['name'];
            }
        }

    }

    public static function gItemBack($field, $id) {
        $className = get_called_class();
        $Obj = new $className();
        $list = $Obj->gVal($field);

        foreach($list as $item) {
            if($item['id'] === $id) {
                return $item;
            }
        }
    }

    /**
     *  table example configuration = [
     *   "table"      => 'lt_client_order',
     *   "foreign"    => 'idOrder',
     *   "colSearch"  => 'id',
     *   "whereWithValue" => [['otherColumn',1],['name','mario']]  or empty
     *   "nameObj"    => 'ClientOrder',
     *   "nested"     => [
     *           "table"         => 'lt_client_type',
     *           "foreign"       => 'id',
     *           'colSearch'     => 'idClientType',
     *           'nameObj'       => 'ClientType',
     *           'nested'        => [
     *                   'table'             => 'lt_clients',
     *                   'foreign'           => 'id',
     *                   'colSearch'         => 'idClient',
     *                   'nameObj'           => 'Client',
     *                   'nested'            => []
     *   
     *               ]
     *       ] 
     * ];
     */

    public function getOtherTableCol($table, $column, \Closure $closure = null) {
       if(!is_null($closure)) {
        $column = $closure($column);
       } 
        if(is_array($table)) {
            $object = $table['nameObj'];
            if(is_null($this->$object)) {
                $search = $table['colSearch'];
                $where = [];
                $where[] = [$table['foreign'],$this->$search];
                if(isset($table['whereWithValue'])) {
                    foreach($table['whereWithValue'] as $valWhere) {
                        $where[] = $valWhere;
                    }
                }
                $db = DB::table($table['table'])->where($where)->first();
                $this->$object = $db;
                if($this->$object) {
                    if(count($table['nested']) === 0) {
                        return $this->$object->$column;
                    } else {
                        return $this->getOTCNested($table['nested'],$column, $this->$object);
                    } 
                } else {
                    return null;
                }
            } else {
                if(count($table['nested']) === 0)
                    return $this->$object->$column;
                else 
                    return $this->getOTCNested($table['nested'],$column, $this->$object);
            }
        }
    }

    protected function getOTCNested($table, $column, &$object) {
        $newObj = $table['nameObj'];

        if(is_null($this->$newObj)) {
            $search = $table['colSearch'];
            $where = [];
            $where[] = [$table['foreign'],$object->$search];
            if(isset($table['whereWithValue'])) {
                foreach($table['whereWithValue'] as $valWhere) {
                    $where[] = $valWhere;
                }
            }
            $object->$newObj = DB::table($table['table'])->where($where)->first();
            if($object->$newObj) {
                if(count($table['nested']) === 0) {
                    return $object->$newObj->$column;
                } else {
                    return $this->getOTCNested($table['nested'],$column, $object->$newObj);
                }
            } else {
                return null;
            }
        } else {
            if(count($table['nested']) === 0) {
                return $object->$newObj->$column;
            } else {
                return $this->getOTCNested($table['nested'],$column, $object->$newObj);
            }
        }
    }


    public static function getAllItems($listData, $listWhere = []) {
       
        $class = get_called_class();
        $items = [];

        if(count($listWhere) === 0)
            $items = $class::all();
        else
            $items = $class::where($listWhere)->get();

        $response = [];

        foreach($items as $it) {
            $response[]['fields'] = $it->getSerializedItem($listData);
        }
        return $response;
    }


    public function getSerializedItem($data) {
        $response = [];
        
        $response[] = ['label' => '', 'value' => '', 'type' => 'checkbox'];
        foreach($data as $item) {
            $field = $item['field'];
            $label = $item['label'];
            $type = $item['type'];
            $class = isset($item['class']) ? $item['class'] : null;

            if(is_array($field)) {
                $value = '';
                 for($i = 0; $i < count($field); $i++)   {
                     $f = $field[$i];
                     if($i != count($field) - 1)
                        $value.= $this->$f.' ';
                    else {
                        $value.= $this->$f;
                    }
                }

                $response[] = ['label' => $label, 'value' => $value, 'type' => $type];
            }
            else {
                if($type === 'date') {
                    $value = date('d-m-Y',strtotime($this->$field));
                    $response[] = ['label' => $label, 'value' => $value , 'type' => 'text'];
                }
                else if($type === 'preset') {
                    $response[] = ['label' => $label, 'value' => self::sanitize(self::gValBack($field, $this->{$field})) , 'type' => 'text'];
                }
                else
                $response[] = ['label' => $label, 'value' => $this->$field , 'type' => $type];
            }
        }

        return $response;
    }

    public static function sanitize($data) {
        return str_replace('&nbsp;','',$data);
    }
}