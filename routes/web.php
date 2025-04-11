<?php

use App\Http\Controllers\ApiController;
use App\Http\Controllers\CertificateController;
use App\Http\Controllers\ExportToExcelController;
use App\Http\Controllers\FormController;
use App\Http\Controllers\V2Controller;
use App\Models\Certificate;
use App\Models\Muestreo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::get('/{lote}/{muestras}/{fila}/{columna}', [V2Controller::class, 'getview'])->name('samples');

Route::get('/', function () {
    $muestreos = Muestreo::orderBy('id', 'desc')->get();
    return view('home', [
        'muestreos' => $muestreos
    ]);
})->name('home');

Route::get('/form', [FormController::class, 'form']);

Route::get('/toexcel/{lote}/{muestras}/{fila}/{columna}',[ExportToExcelController::class, 'toExcel']);
Route::get('/topdf/{lote}/{muestras}/{fila}/{columna}', [V2Controller::class, 'toPdf']);

Route::post('/search', [ApiController::class, 'search'])->name('search');
Route::post('/preview', [ApiController::class, 'preview'])->name('preview');

Route::get('/search/{id}', [ApiController::class, 'search'])->name('searchWithId');

Route::get('/trace/{id}', [ApiController::class, 'trace'])->name('track');
Route::post('/end', [ApiController::class, 'end'])->name('end');
Route::get('/topdf/{id}', [ApiController::class, 'toPdf'])->name('toPdf');

Route::get('/2', function () {
    return view('sistema2', [
        'certificados' => Certificate::where('active', 1)->get()
    ]);
});

Route::get('/sistema2/{certificado}', [ApiController::class, 'sistema2'])->name('sistema2');

Route::get('/2s', [CertificateController::class, 'index'])->name('certificates');
   