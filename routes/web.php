<?php

use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\MpController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;
use MercadoPago\Client\Common\RequestOptions;
use MercadoPago\Client\Order\OrderClient;
use MercadoPago\Exceptions\MPApiException;
use MercadoPago\MercadoPagoConfig;

Route::get('/pdf/{id}', [InvoiceController::class, 'gerar'])->name('pdf.download');
Route::get('/pdf/relatorio/1', [InvoiceController::class, 'relatorioFrequenciaCompras'])->name('pdf.relatorio.1');

Route::view('/', 'pagamento');

Route::post('/pagar', function(Request $request) {
    MercadoPagoConfig::setAccessToken("APP_USR-3903069347230855-052508-880e5b48a23caa2dc1e4fadef469578c-387424373");
    
    $data = $request->all();

    $items = [];

    // foreach($this->products as $productItem) {
        // $product = $productItem['product'];

    $item = [
        "id" => 2,
        "title" => 'Comida teste',
        "description" => 'Testando',
        // "picture_url" => asset($product->getFirstImage()),
        "quantity" => 1,
        "unit_price" => '5.00',
    ];
    $items[] = $item;
    // }
    
    try {
        $request_options = new RequestOptions();
        $request_options->setCustomHeaders(["X-Idempotency-Key: " . Str::uuid()->toString()]);

        $total = (float) 5;
        $transaction_amount = str_replace(',', '', number_format(bcmul($total, 1, 2), 2));

        $requestMp = [
            "token" => $data['token'],
            "payment_method_id" => $data['payment_method_id'],
            "installments" => (int) $data['installments'],
            "additional_info" => [
                "items" => $items,
            ],
            "transaction_amount" => (float) $transaction_amount,
            "payer" => [
                "email" => $data['payer']['email']
            ],
        ];

        $client = new \MercadoPago\Client\Payment\PaymentClient();
        $payment = $client->create($requestMp, $request_options);

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