<?php

namespace LTFramework\Editor\Field;

use LTFramework\Editor\AbstractField;


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