<?php

namespace LTFramework\Services\Editor\Field;

use LTFramework\Services\Editor\AbstractField;



class FileField extends AbstractField{
    protected $type = "filefld";
    protected $obj = null;
    protected $idObj = null;

    protected $dataSerialize = [
        'label',
        'name',
        'data',
        'isEdit',
        'obj',
        'idObj',
        'initialized',

    ];
}