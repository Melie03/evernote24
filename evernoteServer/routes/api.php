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
Route::get('/noteLists/{id}', [App\Http\Controllers\NoteListController::class, 'getById']);
Route::get('/noteLists/user/{userId}', [App\Http\Controllers\NoteListController::class, 'getByUserId']);
Route::get('/noteLists/{id}/notes', [App\Http\Controllers\NoteListController::class, 'getNotesByListId']);
Route::post('/noteLists', [App\Http\Controllers\NoteListController::class, 'store']);
Route::put('/noteLists/{id}', [App\Http\Controllers\NoteListController::class, 'update']);
Route::put('/noteLists/{listId}/share/{userId}', [App\Http\Controllers\NoteListController::class, 'shareList']);
Route::delete('/noteLists/{id}', [App\Http\Controllers\NoteListController::class, 'destroy']);
Route::get('/noteLists/shared/{userId}', [App\Http\Controllers\NoteListController::class, 'getSharedListsByUserId']);
Route::put('/noteLists/{listId}/share/{userId}/accept', [App\Http\Controllers\NoteListController::class, 'acceptSharedList']);
Route::put('/noteLists/{listId}/share/{userId}/decline', [App\Http\Controllers\NoteListController::class, 'declineSharedList']);

// Notes Routes
Route::get('/notes', [App\Http\Controllers\NoteController::class, 'index']);
Route::get('/notes/{id}', [App\Http\Controllers\NoteController::class, 'show']);
Route::post('/notes', [App\Http\Controllers\NoteController::class, 'store']);
Route::put('/notes/{id}', [App\Http\Controllers\NoteController::class, 'update']);
Route::delete('/notes/{id}', [App\Http\Controllers\NoteController::class, 'destroy']);
Route::get('/notes/{id}/todos', [App\Http\Controllers\NoteController::class, 'getTodoByNoteId']);
Route::get('/notes/{id}/tags', [App\Http\Controllers\NoteController::class, 'getTagsByNoteId']);


// Todos Routes
Route::get('/todos', [App\Http\Controllers\TodoController::class, 'index']);
Route::get('/todos/{id}', [App\Http\Controllers\TodoController::class, 'show']);
Route::post('/todos', [App\Http\Controllers\TodoController::class, 'store']);
Route::put('/todos/{id}', [App\Http\Controllers\TodoController::class, 'update']);
Route::delete('/todos/{id}', [App\Http\Controllers\TodoController::class, 'destroy']);
Route::get('/todosw', [App\Http\Controllers\TodoController::class, 'getTodoWithoutNote']);
Route::get('/todos/{id}/tags', [App\Http\Controllers\TodoController::class, 'getTagsByTodoId']);

// Tags Routes
Route::get('/tags', [App\Http\Controllers\TagController::class, 'index']);
Route::get('/tags/{id}', [App\Http\Controllers\TagController::class, 'show']);
Route::post('/tags', [App\Http\Controllers\TagController::class, 'store']);
Route::put('/tags/{id}', [App\Http\Controllers\TagController::class, 'update']);
Route::delete('/tags/{id}', [App\Http\Controllers\TagController::class, 'destroy']);

// Users Routes
Route::get('/users', [App\Http\Controllers\UserController::class, 'index']);
Route::get('/users/{id}', [App\Http\Controllers\UserController::class, 'show']);
Route::post('/users', [App\Http\Controllers\UserController::class, 'store']);
Route::put('/users/{id}', [App\Http\Controllers\UserController::class, 'update']);
Route::delete('/users/{id}', [App\Http\Controllers\UserController::class, 'destroy']);
