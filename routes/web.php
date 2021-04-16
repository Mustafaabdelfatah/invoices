<?php

use Illuminate\Support\Facades\Route;



Route::get('/', function () {
    return redirect('admin');
});

 

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
 