<?php

use App\Models\Drg;
use App\Models\Machine;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DRGController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/kanban-machines', function () {
    $machines = Machine::with('drgs')->get(); // Récupère toutes les machines avec leurs DRGs
    $unassignedDrgs = Drg::whereNull('machine_id')->get(); // Récupère les DRGs sans machine

    return response()->json([
        'machines' => $machines,
        'unassignedDrgs' => $unassignedDrgs,
    ]);
});


Route::post('/update-drg-machine', function (Request $request) {
    $drg = Drg::findOrFail($request->drg_id);
    $drg->machine_id = $request->machine_id;
    $drg->save();
    return response()->json(['message' => 'DRG mis à jour']);
});

Route::post('/update-drg-status', function (Request $request) {
    $drg = Drg::findOrFail($request->drg_id);
    $drg->statu = $request->statu;
    $drg->save();
    return response()->json(['message' => 'DRG mis à jour']);
});

Route::get('/kanban-drgs', [DRGController::class, 'getDrgsByStatus']);