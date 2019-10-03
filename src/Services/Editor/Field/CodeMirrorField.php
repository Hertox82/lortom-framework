<?php

namespace LTFramework\Services\Editor\Field;

use LTFramework\Services\Editor\AbstractField;




class CodeMirrorField extends AbstractField {
    protected $type = 'cdmirror';

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