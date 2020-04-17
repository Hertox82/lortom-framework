<?php

namespace LTFramework\Editor\Field;

use LTFramework\Editor\AbstractField;

class UploadFileField extends AbstractField {

    protected $dataSerialize = [
        'label',
        'placeholder',
        'name',
        'data',
        'saveButton',
        'isEdit',
        'initialized',
        'available',
        'urlToSave'
    ];

    protected $type = 'uplfile';
    protected $saveButton = '';
    protected $urlToSave = '';
}