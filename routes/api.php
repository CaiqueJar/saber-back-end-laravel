<?php

use App\Http\Controllers\Auth\AutenticacaoController;
use App\Http\Controllers\CategoriaRestauranteController;
use App\Http\Controllers\HorarioFuncionamentoController;
use App\Http\Controllers\RestauranteController;
use App\Http\Controllers\ProdutoController;
use App\Http\Controllers\CategoriaProdutoController;
use App\Http\Controllers\MpController;
use App\Http\Controllers\PedidoController;
use App\Http\Controllers\SacolaController;
use App\Http\Controllers\UsuarioController;
use Illuminate\Support\Facades\Route;

Route::get('/categorias/{limit?}', [CategoriaRestauranteController::class, 'list']);

/**
 * ENDPOINTS DO USUÁRIO
 */
Route::prefix('/usuario')
    ->controller(UsuarioController::class)
    ->group(function() {
        Route::get('/pegar-token', 'pegarToken');
        Route::get('/{token}/logout', 'logout');

        Route::post('/enviar-email', 'enviarEmail');
        Route::post('/verificar-codigo', 'validarCodigo');

        Route::post('/', 'cadastrar');

        Route::post('/endereco', 'cadastrarEndereco');
        Route::get('/{id}/enderecos', 'enderecos');
        Route::put('/endereco/{id}', 'atualizarEndereco');
        Route::delete('/endereco/{id}', 'deletarEndereco');

        Route::post('/atualizar', 'atualizar');

        Route::get('/{id}/pedidos', 'getPedidos');
        Route::get('/{id}/pedido/{pedidoId}', 'getPedido');

    });

/**
 * ENDPOINTS DA SACOLA
 */
Route::prefix('/sacola')
    ->controller(SacolaController::class)
    ->group(function () {
        Route::post('/adicionar-item','adicionarItem');
        Route::delete('/remover-item','removerItem');
        Route::post('/itens', 'pegarSacola');
        Route::post('/alterar-quantidade-item', 'alterarQuantidadeItem');
    });


/**
 * ENDPOINTS DO PEDIDO
 */
Route::prefix('/pedido')
    ->controller(PedidoController::class)
    ->group(function () {
        Route::post('/', 'criarPedido');
        Route::post('/{id}/status', 'atualizarPedido');
    });


/**
 * ENDPOINTS DO RESTAURANTE
 */
Route::resource('/restaurante', RestauranteController::class)->except(['create', 'edit']);
Route::prefix('/restaurante')->controller(RestauranteController::class)->group(function () {
    Route::get('/list/deleted', 'listTrashed');
    Route::post('/pesquisa', 'search');

    Route::get('/{id}/endereco', 'getEndereco');
    Route::put('/{id}/restore', 'restore');
    Route::post('/check/email-exists', 'checkIfEmailExists');

    Route::get('/{id}/pedidos', 'getPedidos');

    Route::post('/usuarios/relatorio1', 'getUsuariosRelatorio');

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

    Route::get('redirect/{social}', 'socialiteRedirect');
    Route::get('callback/{social}', 'socialiteCallback');

    Route::post('/google-sign-in-mobile', 'googleSignInMobile');
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

    Route::post('/atualizar/{id}', 'update');
});




/**
 * MERCADO PAGO
 */
Route::post('/webhook', [MpController::class, 'webhook'])->name('mp.webhook');
