<?php


namespace LTFramework\Services\Editor\Field;

use LTFramework\Services\Editor\AbstractField;


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