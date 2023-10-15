<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PdfExtractorController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
phpinfo();
    // return view('welcome');
});

Route::get('/upload-pdf', [PdfExtractorController::class, 'index'])->name('upload-pdf');

Route::post('/parse-pdf', [PdfExtractorController::class, 'parsePdf'])->name('parse-pdf');
