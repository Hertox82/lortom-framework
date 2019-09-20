<?php

namespace LTFramework\Services\Editor\Field;

use LTFramework\Services\Editor\AbstractField;


class NumberField extends AbstractField {
    protected $type = 'number';
    protected $step = 0.01;

    protected $dataSerialize = [
        'label',
        'name',
        'data',
        'isEdit',
        'step',
        'available'
    ];
}