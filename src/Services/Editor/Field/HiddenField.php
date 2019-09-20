<?php 

namespace LTFramework\Services\Editor\Field;

use LTFramework\Services\Editor\AbstractField;


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