<?php

//广告模块
Route::Group([
    'namespace' => 'Admin',
], function () {
    //后台管理部分
    Route::group([
        'prefix'     => 'platform',
        'middleware' => ['auth', 'auth.admin', 'operation.log', 'ban.ip'],
    ], function () {
        // 删除接口
        Route::match(['get', 'post'], 'delete', ['as' => 'admin.platform.delete', 'uses' => 'PlatformController@delete']);
        // 保存接口
        Route::match(['get', 'post'], 'store', ['as' => 'admin.platform.store', 'uses' => 'PlatformController@store']);

        Route::any('update', ['as' => 'admin.platform.update', 'uses' => 'PlatformController@update']);

        //资源路由，自动加载模板及控制器中的方法
        Route::resource('platform', 'PlatformController', ['as' => 'admin', 'only' => ['index', 'edit', 'create', 'option']]);
    });

    Route::group([
        'prefix'     => 'adposition',
        'middleware' => ['auth', 'auth.admin', 'operation.log', 'ban.ip'],
    ], function () {
        // 删除接口
        Route::match(['get', 'post'], 'delete', ['as' => 'admin.adposition.delete', 'uses' => 'AdPositionController@delete']);
        // 保存接口
        Route::match(['get', 'post'], 'store', ['as' => 'admin.adposition.store', 'uses' => 'AdPositionController@store']);
        // 更新接口
        Route::any('update', ['as' => 'admin.adposition.update', 'uses' => 'AdPositionController@update']);

        //资源路由，自动加载模板及控制器中的方法
        Route::resource('adposition', 'AdPositionController', ['as' => 'admin', 'only' => ['index', 'edit', 'create', 'option']]);
    });

    Route::group([
        'prefix'     => 'adimage',
        'middleware' => ['auth', 'auth.admin', 'operation.log', 'ban.ip'],
    ], function () {
        // 删除接口
        Route::match(['get', 'post'], 'delete', ['as' => 'admin.adimage.delete', 'uses' => 'AdImageController@delete']);
        // 保存接口
        Route::match(['get', 'post'], 'store', ['as' => 'admin.adimage.store', 'uses' => 'AdImageController@store']);
        // 更新接口
        Route::any('update', ['as' => 'admin.adimage.update', 'uses' => 'AdImageController@update']);

        //资源路由，自动加载模板及控制器中的方法
        Route::resource('adimage', 'AdImageController', ['as' => 'admin', 'only' => ['index', 'edit', 'create', 'option']]);

    });

    Route::group([
        'prefix'     => 'adtask',
        'middleware' => ['auth', 'auth.admin', 'operation.log', 'ban.ip'],
    ], function () {
        // 删除接口
        Route::match(['get', 'post'], 'delete', ['as' => 'admin.adtask.delete', 'uses' => 'AdTaskController@delete']);
        // 保存接口
        Route::match(['get', 'post'], 'store', ['as' => 'admin.adtask.store', 'uses' => 'AdTaskController@store']);
        // 更新接口
        Route::any('update', ['as' => 'admin.adtask.update', 'uses' => 'AdTaskController@update']);

        //资源路由，自动加载模板及控制器中的方法
        Route::resource('adtask', 'AdTaskController', ['as' => 'admin', 'only' => ['index', 'edit', 'create']]);
    });

    Route::group([
        'prefix'     => 'adtaskdetail',
        'middleware' => ['auth', 'auth.admin', 'operation.log', 'ban.ip'],
    ], function () {
        // 删除接口
        Route::match(['get', 'post'], 'delete', ['as' => 'admin.adtaskdetail.delete', 'uses' => 'AdTaskDetailController@delete']);
        // 更新接口
        Route::any('update', ['as' => 'admin.adtaskdetail.update', 'uses' => 'AdTaskDetailController@update']);

        //资源路由，自动加载模板及控制器中的方法
        Route::resource('adtaskdetail', 'AdTaskDetailController', ['as' => 'admin', 'only' => ['index', 'edit']]);
    });

});

Route::get('adlogin', ['uses' => 'IndexController@adlogin']);
