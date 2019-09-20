<?php

namespace LTFramework\Services\Editor\Field;

use LTFramework\Services\Editor\AbstractField;




class CheckboxListField extends AbstractField {
    protected $type = 'chbxList';
    protected $dataSerialize = [
        'items',
        'name',
        'isEdit',
        'initialized'
    ];
    protected $items = [];
}