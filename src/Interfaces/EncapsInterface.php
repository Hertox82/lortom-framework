<?php 

namespace LTFramework\Interfaces;


interface EncapsInterface {
    public static function getUrl($fileName, $parameters);

    public static function getSlug($fileName, $parameters = []);
}