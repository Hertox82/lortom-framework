<?php

namespace LTFramework\Editor\Field;

use LTFramework\Editor\AbstractField;



class TextareaField extends AbstractField {
    protected $type = 'textarea';

    protected $dataSerialize = [
        'label',
        'name',
        'data',
        'isEdit',
        'initialized',
        'available'
    ];
}