<?php
/**
 * User: hernan
 * Date: 04/07/2018
 * Time: 12:03
 */


Route::get('/',[
    'as'    => 'listApi',
    'uses'  => 'LortomController@listApi'
]);

Route::get('/populate-slidebar',[
    'as'    => 'populateSlidebar',
    'uses'  => 'Backend\BackendController@populate'
])->middleware('lortom.auth');

Route::post('/login',[
    'as'    => 'apiLogin',
    'uses'  => 'Backend\BackendController@requestLogin'
]);

Route::get('/logout',[
    'as'    => 'apiLogout',
    'uses'  => 'Backend\BackendController@requestLogout'
]);

Route::put('/edit-my-profile',[
    'as'     => 'apiEditMyProfile',
    'uses'   => 'Backend\BackendController@requestEditMyProfile'
])->middleware('lortom.auth');