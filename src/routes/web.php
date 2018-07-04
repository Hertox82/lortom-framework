<?php
/**
 * User: hernan
 * Date: 04/07/2018
 * Time: 12:04
 */


Route::get('/backend',function(){
    return view('backend.backend-angular');
})->middleware('lortom.auth');


Route::group(['prefix' => '/backend', 'middleware' => 'lortom.auth'], function(){
    Route::any('{catchAll}', function(){
        return view('backend.backend-angular');
    })->where('catchAll','(.*)');
});

Route::any('{catchAll}',[
    'as'    => 'catchAll',
    'uses'  => 'LortomController@catchAll'
])->where('catchAll','^(?!api|backend).*$');