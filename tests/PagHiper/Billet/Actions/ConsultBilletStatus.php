<?php

use DevAjMeireles\PagHiper\Billet\Actions\ConsultBilletStatus;
use DevAjMeireles\PagHiper\Core\Exceptions\PagHiperRejectException;
use DevAjMeireles\PagHiper\Core\Request\Request;
use DevAjMeireles\PagHiper\PagHiper;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;

it('should be able to consult billet status casting to array', function () {
    $result = [
        'result'           => 'success',
        'response_message' => 'transacao encontrada',
        'status'           => 'pending',
        'status_date'      => '2017-07-14 21:21:02',
        'due_date'         => '2017-07-12',
        'value_cents'      => '2000',
        'bank_slip'        => [
            'digitable_line' => '34191.76106 04487.160246 61514.190000 3 72180000002000',
            'url_slip'       => 'https://www.paghiper.com/checkout/boleto/ XXXXXXXXXXXXXXX',
            'url_slip_pdf'   => 'https://www.paghiper.com/checkout/boleto/XXXXXXXXXXXXXXX/pdf',
        ],
        'http_code' => '201',
    ];

    Http::fake([
        Request::url(ConsultBilletStatus::END_POINT) => Http::response([
            'status_request' => $result]),
    ]);

    $status = (new PagHiper())->billet()->status('BPV661O7AVLORCN5');

    expect($status)
        ->toBeArray()
        ->and($status)
        ->toBe($result);
});

it('should be able to consult billet status casting to collection', function () {
    $result = [
        'result'           => 'success',
        'response_message' => 'transacao encontrada',
        'status'           => 'pending',
        'status_date'      => '2017-07-14 21:21:02',
        'due_date'         => '2017-07-12',
        'value_cents'      => '2000',
        'bank_slip'        => [
            'digitable_line' => '34191.76106 04487.160246 61514.190000 3 72180000002000',
            'url_slip'       => 'https://www.paghiper.com/checkout/boleto/ XXXXXXXXXXXXXXX',
            'url_slip_pdf'   => 'https://www.paghiper.com/checkout/boleto/XXXXXXXXXXXXXXX/pdf',
        ],
        'http_code' => '201',
    ];

    Http::fake([
        Request::url(ConsultBilletStatus::END_POINT) => Http::response([
            'status_request' => $result]),
    ]);

    $status = (new PagHiper())->billet(cast: 'collect')->status('BPV661O7AVLORCN5');

    expect($status)->toBeInstanceOf(Collection::class);
});

it('should be able to consult billet status casting to original response', function () {
    $result = [
        'result'           => 'success',
        'response_message' => 'transacao encontrada',
        'status'           => 'pending',
        'status_date'      => '2017-07-14 21:21:02',
        'due_date'         => '2017-07-12',
        'value_cents'      => '2000',
        'bank_slip'        => [
            'digitable_line' => '34191.76106 04487.160246 61514.190000 3 72180000002000',
            'url_slip'       => 'https://www.paghiper.com/checkout/boleto/ XXXXXXXXXXXXXXX',
            'url_slip_pdf'   => 'https://www.paghiper.com/checkout/boleto/XXXXXXXXXXXXXXX/pdf',
        ],
        'http_code' => '201',
    ];

    Http::fake([
        Request::url(ConsultBilletStatus::END_POINT) => Http::response([
            'status_request' => $result]),
    ]);

    $status = (new PagHiper())->billet(cast: 'response')->status('BPV661O7AVLORCN5');

    expect($status)->toBeInstanceOf(Response::class);
});

it('should be able to throw exception due response reject', function () {
    $this->expectException(PagHiperRejectException::class);
    $this->expectExceptionMessage("token ou apiKey inválidos");

    Http::fake([
        Request::url(ConsultBilletStatus::END_POINT) => Http::response([
            'status_request' => [
                'result'           => 'reject',
                'response_message' => 'token ou apiKey inválidos',
            ]]),
    ]);

    (new PagHiper())->billet()->status('BPV661O7AVLORCN5');
});
