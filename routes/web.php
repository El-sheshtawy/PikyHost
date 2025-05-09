<?php

use App\Http\Controllers\ProjectController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/projects/{project}/documents/{media}', [ProjectController::class, 'showMedia'])->name('projects.documents.show');
