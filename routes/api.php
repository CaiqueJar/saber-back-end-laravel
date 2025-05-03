<?php

use App\Http\Controllers\Auth\AutenticacaoController;
use App\Http\Controllers\CategoriaRestauranteController;
use App\Http\Controllers\RestauranteController;
use App\Http\Controllers\ProdutoController;
use App\Http\Controllers\CategoriaProdutoController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::get('/categorias', [CategoriaRestauranteController::class, 'list']);

/**
 * ENDPOINTS DO RESTAURANTE
 */
Route::prefix('/restaurante')->controller(RestauranteController::class)->group(function () {
    Route::resource('/', RestauranteController::class);
    Route::get('/list/deleted', 'listTrashed');
    Route::put('/{id}/restore', 'restore');
    Route::post('/check/email-exists', 'checkIfEmailExists');
});

/**
 * ENDPOINTS DE AUTENTICAÇÃO
 */
Route::prefix('/auth')->controller(AutenticacaoController::class)->group(function () {
    Route::post('/restaurante/login', 'login');
    Route::get('/restaurante/sair', 'logout');
});

Route::resource('/categoria-produto', CategoriaProdutoController::class);

Route::resource('/produto', ProdutoController::class);

