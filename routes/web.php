<?php

use App\Http\Controllers\BinaryTreeController;
use Illuminate\Support\Facades\Route;

Route::get('/', [BinaryTreeController::class, 'index'])->name('binarytree.index');
Route::get('/binarytree', [BinaryTreeController::class, 'index'])->name('binarytree.index');
Route::post('/register', [BinaryTreeController::class, 'registerUser'])->name('binarytree.register');
Route::delete('/clearusers', [BinaryTreeController::class, 'clearUsers'])->name('binarytree.clear');
