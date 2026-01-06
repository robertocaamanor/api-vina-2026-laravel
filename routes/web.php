<?php

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/docs', function () {
    return view('docs');
});

Route::get('/openapi.yaml', function () {
    $path = base_path('openapi.yaml');

    abort_unless(File::exists($path), 404);

    return response(File::get($path), 200)
        ->header('Content-Type', 'application/yaml; charset=UTF-8');
});
