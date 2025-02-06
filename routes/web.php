<?php

use Illuminate\Support\Facades\Route;


 require __DIR__.'/auth.php';

 require __DIR__.'/dashboard.php';


 Route::get('/access-denied', function () {
    return view('access_denied');
})->name('access.denied');


 