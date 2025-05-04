<?php

use App\Http\Controllers\Auth\AutenticacaoController;
use App\Http\Controllers\CategoriaRestauranteController;
use App\Http\Controllers\HorarioFuncionamentoController;
use App\Http\Controllers\RestauranteController;
use App\Http\Controllers\ProdutoController;
use App\Http\Controllers\CategoriaProdutoController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::get('/categorias', [CategoriaRestauranteController::class, 'list']);

/**
 * ENDPOINTS DO RESTAURANTE
 */
Route::resource('/restaurante', RestauranteController::class);
Route::prefix('/restaurante')->controller(RestauranteController::class)->group(function () {
    Route::get('/list/deleted', 'listTrashed');
    Route::put('/{id}/restore', 'restore');
    Route::post('/check/email-exists', 'checkIfEmailExists');
});

/**
 * ENDPOINTS DE HORÁRIO DE FUNCIONAMENTO DO RESTAURANTE
 */

 Route::resource('/horario-funcionamento', HorarioFuncionamentoController::class);
 Route::prefix('/horario-funcionamento')->controller(HorarioFuncionamentoController::class)->group(function () {
    Route::get('/list/restaurante/{restauranteId}', 'listByRestaurant');
    Route::put('/{id}/restore', 'restore');
 });
 
/**
 * ENDPOINTS DE AUTENTICAÇÃO
 */
Route::prefix('/auth')->controller(AutenticacaoController::class)->group(function () {
    Route::post('/restaurante/login', 'login');
    Route::get('/restaurante/sair', 'logout');
});

/**
 * ENDPOINTS DE CATEGORIA DE PRODUTO
 */
Route::resource('/categoria-produto', CategoriaProdutoController::class);
Route::prefix('/categoria-produto')->controller(CategoriaProdutoController::class)->group(function () {
    Route::get('/list/restaurante/{restauranteId}', 'listByRestaurant');
});


/**
 * ENDPOINTS DE PRODUTO
 */
Route::resource('/produto', ProdutoController::class);
Route::prefix('/produto')->controller(ProdutoController::class)->group(function () {
    Route::get('/list/restaurante/{restauranteId}', 'listByRestaurant');
    Route::get('/list/categoria/{categoriaId}', 'listByCategory');
    Route::put('/{id}/restore', 'restore');
});

