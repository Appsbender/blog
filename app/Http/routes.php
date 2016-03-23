<?php

/*
|--------------------------------------------------------------------------
| Routes File
|--------------------------------------------------------------------------
|
| Here is where you will register all of the routes in an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

use Illuminate\Support\Facades\Response;

Route::get('/', function () {
    return view('welcome');
});

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| This route group applies the "web" middleware group to every route
| it contains. The "web" middleware group is defined in your HTTP
| kernel and includes session state, CSRF protection, and more.
|
*/

Route::group(['middleware' => 'web'], function () {
    Route::auth();

    Route::get('/', 'ContentsController@index');
    Route::get('contents/view/{slug}', 'ContentsController@view');
    Route::resource('comments', 'CommentsController');
});

Route::get('admin', function () {
    return redirect('/admin/blogs');
});

$router->group([
    'namespace' => 'Admin',
    'middleware' => ['middleware' => 'web'],
], function () {
    Route::resource('admin/blogs', 'BlogsController');
});

Route::group(['prefix' => 'api'], function () {
    Route::get('get_all_blog', 'Api\ApisController@index');
    Route::post('create', 'Api\ApisController@add_content');
    Route::post('edit/{blog_id}', 'Api\ApisController@edit_content');
    Route::post('delete/{id}', 'Api\ApisController@remove_content');
});
