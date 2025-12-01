<?php

use App\Http\Controllers\ApiController;
use App\Http\Controllers\CertificateController;
use App\Http\Controllers\EditController;
use App\Http\Controllers\ExportToExcelController;
use App\Http\Controllers\FormController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\V2Controller;
use App\Models\Certificate;
use App\Models\Muestreo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use PHPUnit\Framework\Attributes\Group;


Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    
    Route::put('/profile/firmas', [ProfileController::class, 'updateFirmas'])
        ->name('profile.firmas.update');
    Route::get('/profile/firmas/{user}/create', [ProfileController::class, 'createFirma'])
        ->name('profile.firma.create');
    Route::post('/profile/firmas/{user}', [ProfileController::class, 'storeFirma'])
        ->name('profile.firma.store');

    Route::get('/', function () {
        $muestreos = Muestreo::orderBy('created_at', 'desc')->get();
        $muestreos_iniciados = $muestreos->filter(function ($muestreo) {
            return $muestreo->muestreo != null && $muestreo->trazabilidad == null;
        });
        
        $muestreos_finalizados = $muestreos->filter(function ($muestreo) {
            return $muestreo->muestreo != null && $muestreo->trazabilidad != null;
        });
        return view('home', [
            'muestreos_iniciados' => $muestreos_iniciados,
            'muestreos_finalizados' => $muestreos_finalizados,
        ]);
    })->name('home');

    Route::get('/form', [FormController::class, 'form']);

    Route::get('/input', function () {
        return view('preview');
        })->name('input');

    Route::post('/search', [ApiController::class, 'search'])->name('search');
    Route::post('/preview', [ApiController::class, 'preview'])->name('preview');

    Route::get('/search/{id}', [ApiController::class, 'search'])->name('searchWithId');

    Route::get('/trace/{id}', [ApiController::class, 'trace'])->name('track');
    Route::post('/end', [ApiController::class, 'end'])->name('end');
    Route::get('/topdf/{id}', [ApiController::class, 'toPdf'])->name('toPdf');

    Route::get('/sistema2/{certificado}', [ApiController::class, 'sistema2'])->name('sistema2');

    Route::get('/2', function () {
        return view('sistema2', [
            'certificados' => Certificate::where('active', 1)->get()
        ]);
    })->name('sistema-dos'); 
    
    Route::resource('certificates', CertificateController::class);
    
    Route::get('/edit/{id}', [EditController::class, 'edit'])->name('edit');    
    Route::post('/update/{id}', [EditController::class, 'update'])->name('update');
    Route::get('/delete/{id}', [EditController::class, 'delete'])->name('delete');

});

require __DIR__.'/auth.php';
