<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TodoTaskController;

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

// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('/', [TodoTaskController::class, 'index']);
Route::post('/tasks', [TodoTaskController::class, 'store']);
Route::patch('/tasks/{todo}', [TodoTaskController::class, 'update']);
Route::delete('/tasks/{todo}', [TodoTaskController::class, 'destroy']);
