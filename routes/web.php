<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FakturPdfController;


Route::get('/', function () {
    return view('welcome');
});
Route::get('/faktur/{faktur}/pdf', [FakturPdfController::class, 'unduh'])->name('faktur.pdf');
