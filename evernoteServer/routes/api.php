<?php

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

route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// NoteLists Routes
Route::get('/noteLists', [App\Http\Controllers\NoteListController::class, 'index']);
Route::get('/noteLists/{id}', [App\Http\Controllers\NoteListController::class, 'show']);
Route::post('/noteLists', [App\Http\Controllers\NoteListController::class, 'store']);
Route::put('/noteLists/{id}', [App\Http\Controllers\NoteListController::class, 'update']);
Route::delete('/noteLists/{id}', [App\Http\Controllers\NoteListController::class, 'destroy']);

// Notes Routes
Route::get('/notes', [App\Http\Controllers\NoteController::class, 'index']);
Route::get('/notes/{id}', [App\Http\Controllers\NoteController::class, 'show']);
Route::post('/notes', [App\Http\Controllers\NoteController::class, 'store']);
Route::put('/notes/{id}', [App\Http\Controllers\NoteController::class, 'update']);
Route::delete('/notes/{id}', [App\Http\Controllers\NoteController::class, 'destroy']);

// Todos Routes
Route::get('/todos', [App\Http\Controllers\TodoController::class, 'index']);
Route::get('/todos/{id}', [App\Http\Controllers\TodoController::class, 'show']);
Route::post('/todos', [App\Http\Controllers\TodoController::class, 'store']);
Route::put('/todos/{id}', [App\Http\Controllers\TodoController::class, 'update']);
Route::delete('/todos/{id}', [App\Http\Controllers\TodoController::class, 'destroy']);

// Tags Routes - Assuming you need basic CRUD for tags as well
Route::get('/tags', [App\Http\Controllers\TagController::class, 'index']);
Route::get('/tags/{id}', [App\Http\Controllers\TagController::class, 'show']);
Route::post('/tags', [App\Http\Controllers\TagController::class, 'store']);
Route::put('/tags/{id}', [App\Http\Controllers\TagController::class, 'update']);
Route::delete('/tags/{id}', [App\Http\Controllers\TagController::class, 'destroy']);
