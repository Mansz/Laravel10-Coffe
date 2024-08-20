<?php

use Illuminate\Support\Facades\Route;
use App\Filament\Resources\ProductResource;
use App\Filament\Resources\TransactionResource;
use App\Filament\Resources\ReportResource;
use Filament\Facades\Filament;
use App\Http\Controllers\ReportController;

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
    return view('welcome');
});
Filament::registerResources([
    ProductResource::class,
    TransactionResource::class,
    ReportResource::class,
]);
Route::middleware(['auth'])->group(function () {
    Route::get('/reports', [ReportResource::class, 'index'])->name('reports.index');
    Route::post('/reports', [ReportController::class, 'store']);
Route::post('/reports/sell/{productId}', [ReportController::class, 'sellProduct']);
});
