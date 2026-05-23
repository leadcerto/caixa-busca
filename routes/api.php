<?php

use App\Modules\Imoveis\Controllers\Api\ImovelApiController;
use App\Modules\Leads\Controllers\Api\LeadApiController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Public Endpoints
Route::group(['middleware' => ['throttle:60,1']], function () {
    Route::get('/imoveis', [ImovelApiController::class, 'index'])->name('api.imoveis.index');
    Route::get('/imoveis/{slug}', [ImovelApiController::class, 'show'])->name('api.imoveis.show');
    Route::get('/estados', [ImovelApiController::class, 'estados'])->name('api.estados');
    Route::get('/municipios', [ImovelApiController::class, 'municipios'])->name('api.municipios');
    Route::post('/leads', [LeadApiController::class, 'convert'])->name('api.leads.convert');
});

// Authenticated Endpoints
Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
