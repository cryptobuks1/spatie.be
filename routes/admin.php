<?php

use App\Http\Admin\Controllers\PostcardController;

Route::redirect('login', 'nova/login')->name('login');

Route::permanentRedirect('admin', 'admin/postcards');

Route::get('admin/postcards', [PostcardController::class, 'index']);
Route::post('admin/postcards', [PostcardController::class, 'store']);
Route::delete('admin/postcards/{postcard}', [PostcardController::class, 'delete']);
