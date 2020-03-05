<?php

namespace LTFramework\Services\Editor\Field;

use LTFramework\Services\Editor\AbstractField;



class TableField extends AbstractField {
    protected $type = 'tblfield';
    protected $table = '';
    protected $keyParams = [];
    protected $uniqueKeys = [];
    protected $btnEdit = true;
    protected $btnDelete = true;

    protected $dataSerialize = [
        'isEdit',
        'table',
        'name',
        'keyParams',
        'uniqueKeys',
        'initialized',
        'btnEdit',
        'btnDelete'
    ];
}