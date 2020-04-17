<?php

namespace LTFramework\Editor\Field;

use LTFramework\Editor\AbstractField;




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