<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
| add some description here....
*/


Route::get('/recipes', 'RecipesController@index');
Route::post('/recipes', 'RecipesController@add');
Route::get('/recipes/{id}', 'RecipesController@detail');
Route::patch('/recipes/{id}', 'RecipesController@update');
Route::delete('/recipes/{id}', 'RecipesController@delete');
