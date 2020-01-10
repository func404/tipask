<?php

Route::Group([
    'namespace' => 'Admin',
], function () {
    Route::group([
        'prefix' => 'adposition',
    ], function () {
        Route::any('options', ['as' => 'api.position.option', 'uses' => 'AdPositionController@options']);
    });
});

Route::Group([
    'namespace' => 'Api',
], function () {
    Route::group([
        'prefix' => 'ad',
    ], function () {
        Route::any('pull', 'AdController@pull');
    });
});
