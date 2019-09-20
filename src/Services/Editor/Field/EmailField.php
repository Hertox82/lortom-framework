<?php 


namespace LTFramework\Services\Editor\Field;

use LTFramework\Services\Editor\AbstractField;

class EmailField extends AbstractField {
    protected $type = 'email';

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