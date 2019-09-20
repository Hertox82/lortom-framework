<?php

namespace LTFramework\Services\Editor\Field;

use LTFramework\Services\Editor\AbstractField;

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