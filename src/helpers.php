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