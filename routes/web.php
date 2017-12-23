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

        Route::get('/dashboard', 'CalendarController@index');

        Route::post('/calendar/create', 'CalendarController@createCalendar');

        Route::get('/event/create', 'EventController@createEvent');
        Route::post('/event/create', 'EventController@doCreateEvent');

        Route::post('/event/create/profile/{id}', 'EventController@profileCreateEvent');

        Route::get('/calendar/sync', 'CalendarController@syncCalendar');
        Route::post('/calendar/sync', 'AdminController@doSyncCalendar');

        Route::get('/logout', 'HomeController@logout');

        //profile route
        Route::get('/profile/{id}', 'UserController@profile');
});

