<?php

namespace LTFramework\Editor\Field;

use LTFramework\Editor\AbstractField;


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