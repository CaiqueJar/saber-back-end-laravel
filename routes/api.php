<?php

use App\Http\Controllers\Auth\AutenticacaoController;
use App\Http\Controllers\CategoriaRestauranteController;
use App\Http\Controllers\RestauranteController;
use App\Http\Controllers\ProdutoController;
use App\Http\Controllers\CategoriaProdutoController;
use Illuminate\Support\Facades\Route;

Route::post('/autenticar', [AutenticacaoController::class, 'login']);

Route::get('/categorias', [CategoriaRestauranteController::class, 'list']);

Route::resource('/restaurante', RestauranteController::class);
Route::get('/restaurante/list/deleted', [RestauranteController::class, 'listTrashed']);
Route::put('/restaurante/{id}/restore', [RestauranteController::class, 'restore']);
Route::get('/restaurante/check/email-exists', [RestauranteController::class, 'checkIfEmailExists']);

Route::resource('/categoria-produto', CategoriaProdutoController::class);

Route::resource('/produto', ProdutoController::class);

