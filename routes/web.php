<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UploadController;

Route::get('/', [UploadController::class, 'index']);

Route::post('/upload', [UploadController::class, 'store']);

Route::get('/api/uploads', [UploadController::class, 'apiIndex']);
