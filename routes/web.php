<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    //return success message in json
    return response()->json(['message' => 'Welcome to the News Aggregator API!'], 200);
});
