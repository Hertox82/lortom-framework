<?php

namespace LTFramework\Services\Editor\Field;

use LTFramework\Services\Editor\AbstractField;



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