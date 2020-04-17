<?php

namespace LTFramework\Editor\Field;

use LTFramework\Editor\AbstractField;




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