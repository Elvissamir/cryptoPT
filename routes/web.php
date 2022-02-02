<?php

use Inertia\Inertia;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Application;
use App\Http\Controllers\CryptoController;
use App\Http\Controllers\DemoUserLoginController;
use App\Http\Controllers\PortfolioCryptosController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return redirect(route('portfolioCryptos.show'));
});

// CREATE DEMO USER & LOGIN
Route::post('/loginAsDemo', [DemoUserLoginController::class, 'store'])
     ->name('demoLogin.store');

// PORTFOLIO ROUTES
Route::get('/portfolio', [PortfolioCryptosController::class, 'show'])
     ->middleware(['auth:sanctum'])
     ->name('portfolioCryptos.show');

Route::post('/portfolio/cryptos', [PortfolioCryptosController::class, 'store'])
     ->middleware(['auth:sanctum'])
     ->name('portfolioCryptos.store');

Route::put('/portfolio/cryptos/{crypto:cg_id}', [PortfolioCryptosController::class, 'update'])
     ->middleware(['auth:sanctum'])
     ->name('portfolioCryptos.update');

Route::delete('/portfolio/cryptos/{crypto:cg_id}', [PortfolioCryptosController::class, 'destroy'])
     ->middleware(['auth:sanctum'])
     ->name('portfolioCryptos.destroy');

// CRYPTO ROUTES
Route::get('/cryptos', [CryptoController::class, 'index'])
     ->middleware(['auth:sanctum'])
     ->name('cryptos.index');

Route::get('/cryptos/{crypto:cg_id}', [CryptoController::class, 'show'])
     ->middleware(['auth:sanctum'])
     ->name('cryptos.show');