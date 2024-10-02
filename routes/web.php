<?php

use App\Http\Controllers\BinaryTreeController;
use App\Http\Controllers\ApiController;
use Illuminate\Support\Facades\Route;

Route::get('/', [BinaryTreeController::class, 'index'])->name('binarytree.index');
Route::get('/binarytree', [BinaryTreeController::class, 'index'])->name('binarytree.index');
Route::post('/register', [BinaryTreeController::class, 'registerUser'])->name('binarytree.register');

Route::post('/api/register', [ApiController::class, 'registerUser'])->name('api.register');
Route::post('/api/user/{userId}/add-points', [ApiController::class, 'addPoints'])->name('api.addPoints');
Route::get('/api/user/{userId}/points-summary', [ApiController::class, 'getPointsSummary'])->name('api.pointsSummary');
