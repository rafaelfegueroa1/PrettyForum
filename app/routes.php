<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the Closure to execute when that URI is requested.
|
*/

Route::controller('setup', 'SetupController');
Route::controller('user', 'UserController');
Route::controller('admin', 'AdminController');


Route::controller('forum', 'ForumController');
Route::controller('topic', 'TopicController');
Route::controller('reply', 'ReplyController');




Route::get('/', function()
{
	return View::make('layouts.forum.home');
});

