<?php 

namespace LTFramework\Editor\Field;

use LTFramework\Editor\AbstractField;


class HiddenField extends AbstractField {
    protected $type = 'hidden';

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