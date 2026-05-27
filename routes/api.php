<?php

use Illuminate\Http\Request;
use App\Http\Controllers\GrafanaController;

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

/*
Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
*/

Route::get('/grafana/dashboards', [GrafanaController::class, 'getDashboards']);
Route::get('/grafana/folders', [GrafanaController::class, 'getFolders']);
Route::post('/grafana/dashboards', [GrafanaController::class, 'createDashboard']);
Route::get('/grafana/dashboardsAll', [GrafanaController::class, 'listDashboards']);