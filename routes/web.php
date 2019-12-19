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

Route::get('/', 'HomeController@index');

Route::post('/upload',  'HomeController@upload');

Route::get('/long-task', function(){

    $key = 'task-status-' . auth()->id;
    Redis::del($key);


    $list = File::directories('/');

    foreach ($list as $file) {
        Redis::rpush($key, "processed $file");
        sleep(1);
    }
});


Route::get('task-status', function(){

    $key = 'task-status-' . auth()->id;
    $log = Redis::lrange($key, 0, -1);
    return json_encode($log);
});
