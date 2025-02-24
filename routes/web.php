<?php

use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DRGController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\MachineController;

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
    return view('index');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/home', [HomeController::class, 'index'])->name('home');
    Route::get('/kanban-machine', [DRGController::class, 'kanbanMachine'])->name('kanban-machine');
    Route::get('/planned', [DRGController::class, 'planned'])->name('planned');
    Route::get('/tobeplanned', [DRGController::class, 'tobeplanned'])->name('tobeplanned');
    Route::get('/cut', [DRGController::class, 'cut'])->name('cut');
    Route::get('/delete', [DRGController::class, 'delete'])->name('delete');

    // API routes
    Route::prefix('api')->group(function () {
        Route::get('/drgs-without-machine', [DRGController::class, 'getDrgsWithoutMachine']);
        Route::get('/machines', [MachineController::class, 'index']);
    });
    // Route pour assigner un DRG à une machine avec middleware
    Route::put('/drg/{drg}/assign-machine', [DrgController::class, 'assignMachine'])->middleware('auth');

    // DRG routes
    Route::prefix('drgs')->name('drgs.')->group(function () {
        Route::get('/', [DRGController::class, 'index'])->name('index');
        Route::get('/create', [DRGController::class, 'create'])->name('create');
        Route::post('/', [DRGController::class, 'store'])->name('store');
        Route::get('/{drg}', [DRGController::class, 'show'])->name('show');
        Route::get('/{drg}/edit', [DRGController::class, 'edit'])->name('edit');
        Route::put('/{drg}', [DRGController::class, 'update'])->name('update');
        Route::delete('/{drg}', [DRGController::class, 'destroy'])->name('destroy');
    });

    // Machine routes
    Route::prefix('machines')->name('machines.')->group(function () {
        Route::get('/', [MachineController::class, 'index'])->name('index');
        Route::get('/create', [MachineController::class, 'create'])->name('create');
        Route::post('/', [MachineController::class, 'store'])->name('store');
        Route::get('/{machine}', [MachineController::class, 'show'])->name('show');
        Route::get('/{machine}/edit', [MachineController::class, 'edit'])->name('edit');
        Route::put('/{machine}', [MachineController::class, 'update'])->name('update');
        Route::delete('/{machine}', [MachineController::class, 'destroy'])->name('destroy');
    });

    Route::get('/machine-load', [MachineController::class, 'getMachineLoad']);
    
});

Route::match(['get', 'post'], '/navbar/search', [SearchController::class, 'showNavbarSearchResults']);

require __DIR__.'/auth.php';

Auth::routes();
