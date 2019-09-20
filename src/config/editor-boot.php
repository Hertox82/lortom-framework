<?php

return [
    [
        'method'        => 'bind',
        'abstract'      => 'edit',
        'closure'       => function($app) {
            return new \LTFramework\Services\Editor\BuildEdit();
        }
    ],
    [
        'method'        => 'bind',
        'abstract'      => 'tablist',
        'closure'       => function($app) {
            return new \LTFramework\Services\Editor\TabList();
        }
    ],
    [
        'method'        => 'bind',
        'abstract'      => 'text',
        'closure'       => function($app) {
            return new \LTFramework\Services\Editor\Field\TextField();
        }
    ],
    [
        'method'        => 'bind',
        'abstract'      => 'hidden',
        'closure'       => function($app) {
            return new \LTFramework\Services\Editor\Field\HiddenField();
        }
    ],
    [
        'method'        => 'bind',
        'abstract'      => 'email',
        'closure'       => function($app) {
            return new \LTFramework\Services\Editor\Field\EmailField();
        }
    ],
    [
        'method'        => 'bind',
        'abstract'      => 'select',
        'closure'       => function($app) {
            return new \LTFramework\Services\Editor\Field\SelectField();
        }
    ],
    [
        'method'        => 'bind',
        'abstract'      => 'tinymce',
        'closure'       => function($app) {
            return new \LTFramework\Services\Editor\Field\TinyMceField();
        }
    ],
    [
        'method'        => 'bind',
        'abstract'      => 'chbxList',
        'closure'       => function($app) {
            return new \LTFramework\Services\Editor\Field\CheckboxListField();
        }
    ],
    [
        'method'        => 'bind',
        'abstract'      => 'number',
        'closure'       => function($app) {
            return new \LTFramework\Services\Editor\Field\NumberField();
        }
    ],
    [
        'method'        => 'bind',
        'abstract'      => 'textarea',
        'closure'       => function($app) {
            return new \LTFramework\Services\Editor\Field\TextareaField();
        }
    ],
    [
        'method'        => 'bind',
        'abstract'      => 'file',
        'closure'       => function($app) {
            return new \LTFramework\Services\Editor\Field\FileField();
        }
    ],
    [
        'method'        => 'bind',
        'abstract'      => 'tblfield',
        'closure'       => function($app) {
            return new \LTFramework\Services\Editor\Field\TableField();
        }
    ],
    [
        'method'        => 'bind',
        'abstract'      => 'modal',
        'closure'       => function($app) {
            return new \LTFramework\Services\Editor\Field\ModalField();
        }
    ],
    [
        'method'        => 'bind',
        'abstract'      => 'date',
        'closure'       => function($app) {
            return new \LTFramework\Services\Editor\Field\DateField();
        }
    ],
    [
        'method'        => 'bind',
        'abstract'      => 'uplfile',
        'closure'       => function($app) {
            return new \LTFramework\Services\Editor\Field\UploadFileField();
        }
    ]
];