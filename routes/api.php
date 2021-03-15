<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Our Service API Routes
|--------------------------------------------------------------------------
|
| Here is where we register our API routes for our application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group, adding with this our namespace
|
*/

Route::group(['prefix' => 'v1'], function () {

    Route::namespace('ThirdParty\VersionControlSystems')->group(function () {

        // Here is the main prefix for search module
        Route::group(['prefix' => 'search'], function () {

        // Start Search Repositories Routes Part (inside Search Module)
        Route::namespace('Search\SearchRepositories')->group(function () {
            Route::get('repositories', 'SearchRepositoryController@search');
        });
       // End Search Repositories Route


       /*
       * Here we can create new part of routes for other supported endpoints to search within,
       * according to github api we can also search: users, search commits, search labels and so on.
       */

     });
    });

});