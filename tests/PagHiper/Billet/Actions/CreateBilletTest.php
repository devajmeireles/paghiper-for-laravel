<?php

use DevAjMeireles\PagHiper\Billet\Actions\CreateBillet;
use DevAjMeireles\PagHiper\Core\Exceptions\PagHiperRejectException;
use DevAjMeireles\PagHiper\Core\Exceptions\ResponseCastNotAllowed;
use DevAjMeireles\PagHiper\Core\Request\Request;
use DevAjMeireles\PagHiper\PagHiper;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;

it('should be able to create billet casting to array', function () {
    $url         = 'https://www.paghiper.com/checkout/boleto/180068c7/HF97T5SH2ZQNLF6Z/30039';
    $transaction = 'HF97T5SH2ZQNLF6Z';

    Http::fake([
        Request::url(CreateBillet::END_POINT) => Http::response([
            'create_request' => [
                'result'           => 'success',
                'response_message' => 'transacao criada',
                'transaction_id'   => $transaction,
                'created_date'     => $createdAt = now()->format('Y-m-d H:i:s'),
                'value_cents'      => 1000,
                'status'           => 'pending',
                'order_id'         => 1,
                'due_date'         => $dueDateAt = now()->addDays(2)->format('Y-m-d'),
                'bank_slip'        => [
                    'digitable_line'           => '34191.76304 03906.270248 61514.190000 9 72330000017012',
                    'url_slip'                 => $url,
                    'url_slip_pdf'             => $url . '/pdf',
                    'bar_code_number_to_image' => '34199723300000170121763003906270246151419000',
                ],
            ]]),
    ]);

    $billet = (new PagHiper())->billet()->create();

    expect($billet)
        ->toBeArray()
        ->and($billet)
        ->toBe([
            'result'           => 'success',
            'response_message' => 'transacao criada',
            'transaction_id'   => $transaction,
            'created_date'     => $createdAt,
            'value_cents'      => 1000,
            'status'           => 'pending',
            'order_id'         => 1,
            'due_date'         => $dueDateAt,
            'bank_slip'        => [
                'digitable_line'           => '34191.76304 03906.270248 61514.190000 9 72330000017012',
                'url_slip'                 => $url,
                'url_slip_pdf'             => $url . '/pdf',
                'bar_code_number_to_image' => '34199723300000170121763003906270246151419000',
            ],
        ]);
});

it('should be able to create billet casting to collection', function () {
    $url         = 'https://www.paghiper.com/checkout/boleto/180068c7/HF97T5SH2ZQNLF6Z/30039';
    $transaction = 'HF97T5SH2ZQNLF6Z';

    Http::fake([
        Request::url(CreateBillet::END_POINT) => Http::response([
            'create_request' => [
                'result'           => 'success',
                'response_message' => 'transacao criada',
                'transaction_id'   => $transaction,
                'created_date'     => now()->format('Y-m-d H:i:s'),
                'value_cents'      => 1000,
                'status'           => 'pending',
                'order_id'         => 1,
                'due_date'         => now()->addDays(2)->format('Y-m-d'),
                'bank_slip'        => [
                    'digitable_line'           => '34191.76304 03906.270248 61514.190000 9 72330000017012',
                    'url_slip'                 => $url,
                    'url_slip_pdf'             => $url . '/pdf',
                    'bar_code_number_to_image' => '34199723300000170121763003906270246151419000',
                ],
            ]]),
    ]);

    $billet = (new PagHiper())->billet(cast: 'collect')->create();

    expect($billet)->toBeInstanceOf(Collection::class);
});

it('should be able to create billet casting to original response', function () {
    $url         = 'https://www.paghiper.com/checkout/boleto/180068c7/HF97T5SH2ZQNLF6Z/30039';
    $transaction = 'HF97T5SH2ZQNLF6Z';

    Http::fake([
        Request::url(CreateBillet::END_POINT) => Http::response([
            'create_request' => [
                'result'           => 'success',
                'response_message' => 'transacao criada',
                'transaction_id'   => $transaction,
                'created_date'     => now()->format('Y-m-d H:i:s'),
                'value_cents'      => 1000,
                'status'           => 'pending',
                'order_id'         => 1,
                'due_date'         => now()->addDays(2)->format('Y-m-d'),
                'bank_slip'        => [
                    'digitable_line'           => '34191.76304 03906.270248 61514.190000 9 72330000017012',
                    'url_slip'                 => $url,
                    'url_slip_pdf'             => $url . '/pdf',
                    'bar_code_number_to_image' => '34199723300000170121763003906270246151419000',
                ],
            ]]),
    ]);

    $billet = (new PagHiper())->billet(cast: 'response')->create();

    expect($billet)->toBeInstanceOf(Response::class);
});

it('should not be able to cast response to unacceptable cast', function (string $cast) {
    $this->expectException(ResponseCastNotAllowed::class);
    $this->expectExceptionMessage("The response cast: $cast is not allowed");

    Http::fake([
        Request::url(CreateBillet::END_POINT) => Http::response([
            'create_request' => [
                'result'           => 'success',
                'response_message' => 'transacao criada',
                'transaction_id'   => 'HF97T5SH2ZQNLF6Z',
                'created_date'     => now()->format('Y-m-d H:i:s'),
                'value_cents'      => 1000,
                'status'           => 'pending',
                'order_id'         => 1,
                'due_date'         => now()->addDays(2)->format('Y-m-d'),
                'bank_slip'        => [
                    'digitable_line'           => '34191.76304 03906.270248 61514.190000 9 72330000017012',
                    'url_slip'                 => 'https://www.paghiper.com/checkout/boleto/180068c7/HF97T5SH2ZQNLF6Z/30039',
                    'url_slip_pdf'             => 'https://www.paghiper.com/checkout/boleto/180068c7/HF97T5SH2ZQNLF6Z/30039/pdf',
                    'bar_code_number_to_image' => '34199723300000170121763003906270246151419000',
                ],
            ]]),
    ]);

    (new PagHiper())->billet(cast: $cast)->create();
})->with([
    ['foobar'],
    ['blabla'],
    ['qwerty'],
]);

it('should be able to throw exception due response reject', function () {
    $this->expectException(PagHiperRejectException::class);
    $this->expectExceptionMessage("transaction_id não informada ou inválida");

    Http::fake([
        Request::url(CreateBillet::END_POINT) => Http::response([
            'create_request' => [
                'result'           => 'reject',
                'response_message' => 'transaction_id não informada ou inválida',
            ]]),
    ]);

    (new PagHiper())->billet()->create();
});
