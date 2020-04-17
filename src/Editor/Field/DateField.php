<?php

namespace LTFramework\Editor\Field;

use LTFramework\Editor\AbstractField;

class DateField extends AbstractField {
    protected $type = 'date';
    protected $dataSerialize = [
        'label',
        'placeholder',
        'name',
        'data',
        'isEdit',
        'initialized',
        'available'
    ];
}