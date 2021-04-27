<?php

return [
    [
        'method'        => 'bind',
        'abstract'      => 'lt.edit',
        'closure'       => function($app) {
            return new \LTFramework\Editor\BuildEdit();
        }
    ],
    [
        'method'        => 'bind',
        'abstract'      => 'lt.tablist',
        'closure'       => function($app) {
            return new \LTFramework\Editor\TabList();
        }
    ],
    [
        'method'        => 'bind',
        'abstract'      => 'lt.text',
        'closure'       => function($app) {
            return new \LTFramework\Editor\Field\TextField();
        }
    ],
    [
        'method'        => 'bind',
        'abstract'      => 'lt.hidden',
        'closure'       => function($app) {
            return new \LTFramework\Editor\Field\HiddenField();
        }
    ],
    [
        'method'        => 'bind',
        'abstract'      => 'lt.email',
        'closure'       => function($app) {
            return new \LTFramework\Editor\Field\EmailField();
        }
    ],
    [
        'method'        => 'bind',
        'abstract'      => 'lt.select',
        'closure'       => function($app) {
            return new \LTFramework\Editor\Field\SelectField();
        }
    ],
    [
        'method'        => 'bind',
        'abstract'      => 'lt.tinymce',
        'closure'       => function($app) {
            return new \LTFramework\Editor\Field\TinyMceField();
        }
    ],
    [
        'method'        => 'bind',
        'abstract'      => 'lt.chbxList',
        'closure'       => function($app) {
            return new \LTFramework\Editor\Field\CheckboxListField();
        }
    ],
    [
        'method'        => 'bind',
        'abstract'      => 'lt.number',
        'closure'       => function($app) {
            return new \LTFramework\Editor\Field\NumberField();
        }
    ],
    [
        'method'        => 'bind',
        'abstract'      => 'lt.textarea',
        'closure'       => function($app) {
            return new \LTFramework\Editor\Field\TextareaField();
        }
    ],
    [
        'method'        => 'bind',
        'abstract'      => 'lt.file',
        'closure'       => function($app) {
            return new \LTFramework\Editor\Field\FileField();
        }
    ],
    [
        'method'        => 'bind',
        'abstract'      => 'lt.tblfield',
        'closure'       => function($app) {
            return new \LTFramework\Editor\Field\TableField();
        }
    ],
    [
        'method'        => 'bind',
        'abstract'      => 'lt.modal',
        'closure'       => function($app) {
            return new \LTFramework\Editor\Field\ModalField();
        }
    ],
    [
        'method'        => 'bind',
        'abstract'      => 'lt.date',
        'closure'       => function($app) {
            return new \LTFramework\Editor\Field\DateField();
        }
    ],
    [
        'method'        => 'bind',
        'abstract'      => 'lt.uplfile',
        'closure'       => function($app) {
            return new \LTFramework\Editor\Field\UploadFileField();
        }
    ],
    [
        'method'        => 'bind',
        'abstract'      => 'lt.cdmirror',
        'closure'       => function($app) {
            return new \LTFramework\Editor\Field\CodeMirrorField();
        }
    ],
    [
        'method'        => 'bind',
        'abstract'      => 'lt.media',
        'closure'       => function($app) {
            return new \LTFramework\Editor\Field\MediaField();
        }
    ]
];