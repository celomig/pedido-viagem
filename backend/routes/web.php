<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Route::any('{any}', function () {
//     abort(404); 
// })->where('any', '.*');
