<?php 


namespace LTFramework\Editor\Field;

use LTFramework\Editor\AbstractField;

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