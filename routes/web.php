<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
//route root
Route::get('/', 'HomeController@index');

//login route
Route::get('/login', 'HomeController@login');
//group of routes for our adminController functions
Route::group(
    ['middleware' => ['admin']],
    function(){

        Route::get('/dashboard', 'AdminController@index');

        Route::get('/calendar/create', 'AdminController@createCalendar');
        Route::post('/calendar/create', 'AdminController@doCreateCalendar');

        Route::get('/event/create', 'AdminController@createEvent');
        Route::post('/event/create', 'AdminController@doCreateEvent');

        Route::post('/event/create/profile/{id}', 'AdminController@profileCreateEvent');

        Route::get('/calendar/sync', 'AdminController@syncCalendar');
        Route::post('/calendar/sync', 'AdminController@doSyncCalendar');

        Route::get('/events', 'AdminController@listEvents');

        Route::get('/logout', 'AdminController@logout');

        //profile route
        Route::get('/profile/{id}', 'UserController@profile');
});

