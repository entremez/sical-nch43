<?php

use App\Http\Controllers\ApiController;
use App\Http\Controllers\BrandController;
use App\Http\Controllers\CertificateController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\DesignationController;
use App\Http\Controllers\ExportToExcelController;
use App\Http\Controllers\FormController;
use App\Http\Controllers\MovementController;
use App\Http\Controllers\ProductAssignmentController;
use App\Http\Controllers\ProductModelController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\V2Controller;
use App\Models\Certificate;
use App\Models\Muestreo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use PHPUnit\Framework\Attributes\Group;



Route::get('/brands/search', [BrandController::class, 'search'])->name('brands.search');
Route::get('/denomination/search', [DesignationController::class, 'search'])->name('denomination.search');
Route::get('/clientes/search', [CustomerController::class, 'search'])->name('customers.search');
Route::get('/modelos/search', [ProductModelController::class, 'search']);


Route::get('/products/assign', [ProductAssignmentController::class, 'assignToServiceOrder'])->name('products.assign');
Route::post('/products/assign', [ProductAssignmentController::class, 'storeAssignment'])->name('products.assign.store');
Route::get('/products/unassigned', [ProductAssignmentController::class, 'unassignedProduct'])->name('products.unassigned');


Route::resource('models', ProductModelController::class);
Route::resource('brands', BrandController::class);
Route::resource('designations', DesignationController::class);
Route::resource('movements', MovementController::class);
Route::resource('customers', CustomerController::class);

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

});

require __DIR__.'/auth.php';
