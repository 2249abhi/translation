<?php

	Route::group(['prefix' => 'translation',['middleware' => 'web','auth']], function($router)
        {
            Route::get('view/{groupKey?}', '\Translation\Controller@getView')->where('groupKey', '.*');
            Route::get('/{groupKey?}', '\Translation\Controller@getIndex')->where('groupKey', '.*');
            Route::post('/add/{groupKey}', '\Translation\Controller@postAdd')->where('groupKey', '.*');
            Route::post('/edit/{groupKey}', '\Translation\Controller@postEdit')->where('groupKey', '.*');
            Route::post('/delete/{groupKey}/{translationKey}', '\Translation\Controller@postDelete')->where('groupKey', '.*');
            Route::post('/import', '\Translation\Controller@postImport');
            Route::post('/find', '\Translation\Controller@postFind');
            Route::post('/publish/{groupKey}', '\Translation\Controller@postPublish')->where('groupKey', '.*');
        });
