<?php

namespace LTFramework\Services\Editor\Field;

use LTFramework\Services\Editor\AbstractField;



class ModalField extends AbstractField {
    protected $type = 'modal';
    protected $fields = [];
    protected $title = '';

    protected $dataSerialize = [
        'title',
        'fields',
        'tblKeyIndex',
        'initialized'
    ];
}