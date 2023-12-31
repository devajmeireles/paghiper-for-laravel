<?php

use DevAjMeireles\PagHiper\Actions\Billet\StatusBillet;
use DevAjMeireles\PagHiper\Enums\Cast;
use DevAjMeireles\PagHiper\Exceptions\PagHiperRejectException;
use DevAjMeireles\PagHiper\PagHiper;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Collection;

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

    fakeBilletResponse(StatusBillet::END_POINT, 'status_request', $result);

    $status = (new PagHiper())->billet()->status('BPV661O7AVLORCN5');

    expect($status)
        ->toBeArray()
        ->and($status)
        ->toBe($result);
});

it('should be able to consult billet status casting to json', function () {
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

    fakeBilletResponse(StatusBillet::END_POINT, 'status_request', $result);

    $status = (new PagHiper())->billet(Cast::Json)->status('BPV661O7AVLORCN5');

    expect($status)
        ->toBeJson()
        ->and($status)
        ->toBe(collect($result)->toJson());
});

it('should be able to consult billet status casting to collection', function (Cast $cast) {
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

    fakeBilletResponse(StatusBillet::END_POINT, 'status_request', $result);

    $status = (new PagHiper())->billet($cast)->status('BPV661O7AVLORCN5');

    expect($status)->toBeInstanceOf(Collection::class);
})->with([
    Cast::Collect,
    Cast::Collection,
]);

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

    fakeBilletResponse(StatusBillet::END_POINT, 'status_request', $result);

    $status = (new PagHiper())->billet(Cast::Response)->status('BPV661O7AVLORCN5');

    expect($status)
        ->toBeInstanceOf(Response::class)
        ->and($status->json('status_request'))
        ->toBe($result);
});

it('should be able to throw exception due response reject', function () {
    $this->expectException(PagHiperRejectException::class);
    $this->expectExceptionMessage("token ou apiKey inválidos");

    $result = [
        'result'           => 'reject',
        'response_message' => 'token ou apiKey inválidos',
    ];

    fakeBilletResponse(StatusBillet::END_POINT, 'status_request', $result);

    (new PagHiper())->billet()->status('BPV661O7AVLORCN5');
});
