<?php

namespace LTFramework\Editor\Field;

use LTFramework\Editor\AbstractField;



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