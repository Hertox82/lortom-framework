<?php
/**
 * User: hernan
 * Date: 23/10/2017
 * Time: 15:05
 */


if(! function_exists('pr')){

    function pr($data,$die = false)
    {
        echo '<pre>';
        print_r($data);
        echo '</pre>';

        if($die)
            die();
    }
}

if(! function_exists('array_map_collection')) {

    function array_map_collection(Closure $callback, $list)
    {
        $return = [];

        foreach ($list as $item)
        {
            $return[] = $callback($item);
        }

        return $return;
    }
}

if(! function_exists('ltpm_config')) {

    function ltpm_config() {

        return app('ltpm')->config();
    }
}

if(! function_exists('ltpm')) {

    function ltpm() {
        return app('ltpm');
    }
}

if(! function_exists('template_path')) {

    function template_path($path='') {

        $config = ltpm_config();

        return (isset($config['deplt'])) ? base_path()."/{$config['deplt']}".$path : base_path().'/template'.$path;

    }
}

if(! function_exists('getObjectFromValueKey')) {

    function getObjectFromValueKey($listArray,$key,$value) {

        foreach ($listArray as $object) {

            if($object[$key] === $value)
            {
                return $object;
            }
        }

        return null;
    }
}

if(! function_exists('build_iteration_order_array')) {

    function build_iteration_order_array($arrDati, $idFieldName, $iterationFieldName){

        // Inizializza
        $arrReturn = array();
        $idPadreAttuale = 0;
        $countSicurezza = 0;

        // Attiva l'iterazione
        $arrReturn = splice($arrDati, $idFieldName, $iterationFieldName, $idPadreAttuale);

        // Restituzione
        return $arrReturn;
    }
}

if(!function_exists("splice"))
{
    function splice($arrDati, $idFieldName, $iterationFieldName, $idPadreAttuale, $arrSpliced = array())
    {
        // Scansione array dei nodi
        foreach($arrDati as $nodo)
        {
            if($nodo[$iterationFieldName] == $idPadreAttuale)
            {
                $arrIterSpliced = splice($arrDati, $idFieldName, $iterationFieldName, $nodo[$idFieldName]);
                $arrSpliced[] = array(
                    "arrDati" => $nodo,
                    "arrChild" => $arrIterSpliced
                );
            }
        }

        // Restituzione
        return $arrSpliced;
    }
}

if(!function_exists("addCategory"))
{
    function addCategory($arrCategorie, $arrReturn, $lvl = 0)
    {
        foreach($arrCategorie as $categoria)
        {
            $categoria_id = $categoria['arrDati']['id'];
            $categoria_label = "&nbsp;&nbsp;&nbsp;&nbsp;" . $categoria['arrDati']['name'];
            for($i=0; $i<$lvl; $i++) $categoria_label = "&nbsp;&nbsp;&nbsp;&nbsp;" . $categoria_label;
            $arrReturn[count($arrReturn)] = array("id"=>$categoria_id, "name"=>$categoria_label);
            if(count($categoria['arrChild']) != 0)
            {
                $arrReturn = addCategory($categoria['arrChild'], $arrReturn, ($lvl+1));
            }
        }
        return $arrReturn;
    }
}

if(!function_exists('tbl')) {

    function tbl(array $data) {
        return app('tablist')->init($data);
    }
}

if(! function_exists('edit')) {
    
    function edit(array $data) {
        return app('edit')->init($data);
    }
}

if(! function_exists('multisite')) {
    
    function multisite() {
        return app('lt-multisitemanager');
    }
}

if(!function_exists('getProvince')) {

    function getProvince() {
        return app('lt.province');
    }
}

if(!function_exists('getPayment')) {
    function getPayment() {
        $return = [];
        $return[] = ['id' => 1 ,  'name' => 'Contrassegno'];
        $return[] = ['id' => 2 ,  'name' => 'RiBa 30gg data fatt.'];
        $return[] = ['id' => 3 ,  'name' => 'Bonifico Anticipato'];
        $return[] = ['id' => 4 ,  'name' => 'Carta di Credito'];
        $return[] = ['id' => 5 ,  'name' => 'Contrassegno'];
        $return[] = ['id' => 6,   'name' => 'Rim. Diretta 7gg data fatt.'];
        $return[] = ['id' => 7,   'name' => 'Rim. Diretta 30gg data fatt.'];
        $return[] = ['id' => 8,   'name' => 'Come convenuto'];

        return $return;
    }
}

if(!function_exists('getSaveList')) {
    function getSaveList($data, $search, $array) {
        $toSave = [];
        foreach($array as $valor) {
            if(is_array($valor)) {
                list($key,$closure) = $valor;
                $newKey = str_replace($search,'',$key);
                if(isset($data[$key]))
                    $toSave[$newKey] = $closure($data[$key]);
            } else {
                $newKey = str_replace($search,'',$valor);
                if(isset($data[$valor]))
                    $toSave[$newKey] = $data[$valor]; 
            }
        }
        return $toSave;
    }
}

if(!function_exists('varexport')) {
    function varexport($expression, $return = false) {
        $export = var_export($expression, true);
        $export = preg_replace("/^([ ]*)(.*)/m", '$1$1$2', $export);
        $array = preg_split("/\r\n|\n|\r/", $export);
        $array = preg_replace(["/\s*array\s\($/", "/\)(,)?$/", "/\s=>\s$/"], [NULL, ']$1', ' => ['], $array);
        $export = join(PHP_EOL, array_filter(["["] + $array));
        if ((bool)$return) return $export; else pr($export);
    }
}