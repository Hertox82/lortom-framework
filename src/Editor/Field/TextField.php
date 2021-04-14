<?php


namespace LTFramework\Editor\Field;

use LTFramework\Editor\AbstractField;


class TextField extends AbstractField {

    protected $dataSerialize = [
        'label',
        'placeholder',
        'name',
        'data',
        'isEdit',
        'initialized',
        'available'
    ];

    protected $type = 'text';
}