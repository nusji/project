<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Route;

abstract class Controller
{
    public function __construct()
    {
        Route::middleware('auth');
    }
}
