<?php

use App\Http\Controllers\ActivityController;
use App\Http\Controllers\ApiController;
use App\Http\Controllers\ApiScpController;
use App\Http\Controllers\AplicationDocumentActivityController;
use App\Http\Controllers\AplicationDocumentController;
use App\Http\Controllers\CertificateController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\EditController;
use App\Http\Controllers\ExportToExcelController;
use App\Http\Controllers\FormController;
use App\Http\Controllers\PlanActivityController;
use App\Http\Controllers\PlanFileController;
use App\Http\Controllers\ProcessController;
use App\Http\Controllers\ProductModelController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TypeCertificateController;
use App\Http\Controllers\V2Controller;
use App\Models\Certificate;
use App\Models\Muestreo;
use App\Models\TypeCertificate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use PHPUnit\Framework\Attributes\Group;

Route::get('test', function () {
    return ApiScpController::clients();
})->name('test');


Route::get('aplication-documents/{id}/edit-activities', [AplicationDocumentActivityController::class, 'edit'])->name('aplication-documents.edit-activities');
Route::put('aplication-documents/{id}/update-activities', [AplicationDocumentActivityController::class, 'update'])->name('aplication-documents.update-activities');
Route::post('plan-activities/{plan}/update', [PlanActivityController::class, 'update'])->name('plan-activities.update');
Route::post('processes/{process}/activities/update', [PlanActivityController::class, 'massUpdate'])->name('plan-activities.mass-update');
Route::delete('/plan-files/{file}', [PlanActivityController::class, 'destroy'])->name('plan-files.destroy');



Route::resource('processes', ProcessController::class);
Route::resource('documents', DocumentController::class);
Route::resource('activities', ActivityController::class);
Route::resource('aplication-documents', AplicationDocumentController::class);
Route::resource('certificate', CertificateController::class);
Route::resource('product-models', ProductModelController::class);
Route::resource('type-certificates', TypeCertificateController::class);



Route::get('aplication-documents/{id}/activities', function ($id) {
    $document = App\Models\AplicationDocument::with('activities')->findOrFail($id);
    return response()->json($document->activities->pluck('id'));
});
// routes/web.php o routes/api.php
Route::get('/api/clientes', [ApiScpController::class, 'clientes'])->name('api.clientes');
Route::get('/api/fabricas', [ApiScpController::class, 'factories'])->name('api.fabricas');


Route::delete('/certificates/{certificate}/models/{model}', [CertificateController::class, 'detachModel'])->name('certificates.detach-model');
Route::post('/certificates/{certificate}/models', [CertificateController::class, 'attachModel'])->name('certificates.attach-model');


Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

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
