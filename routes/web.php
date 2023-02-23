<?php

use App\Http\Controllers\TodoController;
use Illuminate\Support\Facades\Route;

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
    return view('welcome');
});


Route::group(['prefix'=>'todo/v1'], function() {
    Route::get("", [TodoController::class, "listTodo"]);

    Route::get("{id}", [TodoController::class, "getTodo"]);

    Route::post("/addTodo/", [TodoController::class, "saveTodo"]);

    Route::get("/status/{status}", [TodoController::class, "getTodoByStatus"]);

    Route::post("updateTodo/{id}", [TodoController::class, "updateTodo"]);

    Route::patch("status/{id}", [TodoController::class, "updateTodoByStatus"]);

    Route::delete("delete/{id}", [TodoController::class, "delete"]);
});


