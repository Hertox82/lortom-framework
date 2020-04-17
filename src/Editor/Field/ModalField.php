<?php

namespace LTFramework\Editor\Field;

use LTFramework\Editor\AbstractField;



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