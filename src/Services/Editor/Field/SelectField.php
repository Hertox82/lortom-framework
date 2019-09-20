<?php

namespace LTFramework\Services\Editor\Field;

use LTFramework\Services\Editor\AbstractField;


class SelectField extends AbstractField {

    protected $type = 'select';

    protected $dataSerialize = [
        'label',
        'placeholder',
        'name',
        'data',
        'isEdit',
        'options',
        'initialized',
        'available'
    ];

    protected $options = [];
}