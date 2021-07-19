<?php

use App\Http\Controllers\PortfolioCryptosController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

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
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::middleware(['auth:sanctum', 'verified'])->get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->name('dashboard');


//STYLES
Route::get('/styles', function() {
     return view('layout');
});

//Portfolio Routes
Route::get('/portfolio', [PortfolioCryptosController::class, 'show'])
     ->middleware(['auth:sanctum'])
     ->name('portfolioCryptos.show');

Route::post('/portfolio/cryptos', [PortfolioCryptosController::class, 'store'])
     ->middleware(['auth:sanctum'])
     ->name('portfolioCryptos.store');

Route::put('/portfolio/cryptos/{crypto}', [PortfolioCryptosController::class, 'update'])
     ->middleware(['auth:sanctum'])
     ->name('portfolioCryptos.update');

Route::delete('/portfolio/cryptos/{crypto}', [PortfolioCryptosController::class, 'destroy'])
     ->middleware(['auth:sanctum'])
     ->name('portfolioCryptos.destroy');
