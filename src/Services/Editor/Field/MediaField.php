<?php

namespace LTFramework\Services\Editor\Field;

use LTFramework\Services\Editor\AbstractField;


class MediaField extends AbstractField {
    protected $type = 'media';
    protected $src = '';
    protected $isPdf = false;
    protected $fileName = '';
    protected $dataSerialize = [
        'label',
        'name',
        'data',
        'isEdit',
        'src',
        'initialized',
        'available',
        'isPdf',
        'fileName'
    ];
}