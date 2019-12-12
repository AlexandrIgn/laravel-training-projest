<?php

Auth::routes();

Route::get('/', 'HomeController@index')->name('home');

Route::get('/verify/{token}', 'Auth\RegisterController@verify')->name('register.verify');

Route::get('/cabinet', 'Cabinet\HomeController@index')->name('cabinet');

Route::group([
        'prefix' => 'admin',
        'middleware' => ['auth', 'can:admin-panel'],
        'namespace' => 'Admin',
        'as' => 'admin.'
    ], function () {
        Route::get('/', 'HomeController@index')->name('home');
        Route::resource('/users', 'UsersController');
        Route::post('users/{user}/verify', 'UsersController@verify')->name('users.verify');

        Route::resource('/regions', 'RegionController');

        Route::group(['prefix' => 'adverts', 'as' => 'adverts.', 'namespace' => 'Adverts'], function () {
            Route::resource('categories', 'CategoryController');

            Route::group(['prefix' => 'categories/{category}', 'as' => 'categories.'], function () {
                Route::post('/first', 'CategoryController@first')->name('first');
                Route::post('/up', 'CategoryController@up')->name('up');
                Route::post('/down', 'CategoryController@down')->name('down');
                Route::post('/last', 'CategoryController@last')->name('last');
                
                Route::resource('attributes', 'AttributeController')->except('index');
            });
        });
    });
