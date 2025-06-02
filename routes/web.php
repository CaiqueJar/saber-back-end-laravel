<?php

use App\Http\Controllers\MpController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;
use MercadoPago\Client\Common\RequestOptions;
use MercadoPago\Client\Order\OrderClient;
use MercadoPago\Exceptions\MPApiException;
use MercadoPago\MercadoPagoConfig;

Route::view('/', 'pagamento');

Route::post('/pagar', function(Request $request) {
    MercadoPagoConfig::setAccessToken("APP_USR-3903069347230855-052508-880e5b48a23caa2dc1e4fadef469578c-387424373");
    
    $data = $request->all();
    
    try {
        $paymentRequest = [
            "transaction_amount" => (float)$data['transaction_amount'],
            "token" => $data['token'],
            "description" => $data['description'],
            "installments" => (int)$data['installments'],
            "payment_method_id" => $data['payment_method_id'],
            "issuer_id" => $data['issuer_id'],
            "payer" => [
                "email" => $data['payer']['email'],
                "identification" => [
                    "type" => $data['payer']['identification']['type'],
                    "number" => $data['payer']['identification']['number']
                ]
            ],
            "binary_mode" => true
        ];

        $client = new \MercadoPago\Client\Payment\PaymentClient();
        $payment = $client->create($paymentRequest);

        return response()->json([
            'status' => $payment->status,
            'status_detail' => $payment->status_detail,
            'id' => $payment->id
        ]);
        
    } catch (MPApiException $e) {
        $error = $e->getApiResponse()->getContent();
        Log::error('Erro MP Detalhado:', [
            'status' => $e->getApiResponse()->getStatusCode(),
            'error' => $error,
            'request' => $data
        ]);
        
        return response()->json([
            'error' => 'Pagamento falhou',
            'status' => $e->getApiResponse()->getStatusCode(),
            'details' => $error['cause'] ?? $error['message'] ?? $error
        ], 400);
    }
})->name('pagar');


Route::get('/mp-auth', [MpController::class, 'connectToMercadoPago']);
Route::get('/mp-return', [MpController::class, 'redirect'])->name('mp.return');
Route::post('/webhook', [MpController::class, 'webhook'])->name('mp.webhook');