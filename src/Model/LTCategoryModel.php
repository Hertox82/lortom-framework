<?php 

namespace LTFramework\Model;

use DB;

class LTCategoryModel extends LTModel {

    public function gVal($field,$extra = array())
    {
        $return =[];

        if($field == 'idFather')
        {
            $return = $this->getCatTree();
        }

        return $return;
    }

    public function getCatTree()
    {
        $id = ( !$this->exists ) ? null : $this->id;

        $listCat = DB::table($this->table)->where('id','!=',$id)->get();

        $arrCategorie = [];

        $arrReturn = [];
        $arrReturn[count($arrReturn)] = array("id"=> 0, "name"=>"_root");

        foreach ($listCat as $Cat)
        {
            $arrCategorie[] = ['id' => $Cat->id,'idFather' => $Cat->idFather, 'name' => $Cat->name];
        }

        $arrCategorie = build_iteration_order_array($arrCategorie, "id", "idFather");

       
        $arrReturn = addCategory($arrCategorie, $arrReturn);


        return $arrReturn;
    }

    public function deleteAllChildren() {
            $className = get_called_class();
            $listChild = $className::where('idFather',$this->id)->get();

            foreach($listChild as $child) {
                $child->deleteAllChildren();
                $child->delete();
            }
    }
}