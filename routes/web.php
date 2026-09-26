<?php

use Illuminate\Support\Facades\Route;

Route::view('/', 'home')->name('home');
Route::view('/ui-kit', 'ui-kit')->name('ui-kit');
