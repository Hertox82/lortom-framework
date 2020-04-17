<?php

namespace LTFramework\Editor\Field;

use LTFramework\Editor\AbstractField;




class TinyMceField extends AbstractField {
    protected $type = 'tinymce';

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