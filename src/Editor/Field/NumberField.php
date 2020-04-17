<?php

namespace LTFramework\Editor\Field;

use LTFramework\Editor\AbstractField;


class NumberField extends AbstractField {
    protected $type = 'number';
    protected $step = 0.01;

    protected $dataSerialize = [
        'label',
        'name',
        'data',
        'isEdit',
        'step',
        'initialized',
        'available'
    ];
}