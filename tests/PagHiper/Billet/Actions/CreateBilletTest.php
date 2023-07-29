<?php

use DevAjMeireles\PagHiper\Billet\Actions\CreateBillet;
use DevAjMeireles\PagHiper\Core\Exceptions\{PagHiperRejectException, ResponseCastNotAllowed};
use DevAjMeireles\PagHiper\PagHiper;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Collection;

it('should be able to create billet casting to array', function () {
    $result = [
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
    ];

    fakeBilletResponse(CreateBillet::END_POINT, 'create_request', $result);

    $billet = (new PagHiper())->billet()->create(fakeBilletCreationBody());

    expect($billet)
        ->toBeArray()
        ->and($billet)
        ->toBe($result);
});

it('should be able to create billet casting to collection', function (string $cast) {
    $result = [
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
    ];

    fakeBilletResponse(CreateBillet::END_POINT, 'create_request', $result);

    $billet = (new PagHiper())->billet($cast)->create(fakeBilletCreationBody());

    expect($billet)->toBeInstanceOf(Collection::class);
})->with(['collection', 'collect']);

it('should be able to create billet casting to original response', function () {
    $result = [
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
    ];

    fakeBilletResponse(CreateBillet::END_POINT, 'create_request', $result);

    $billet = (new PagHiper())->billet(cast: 'response')->create(fakeBilletCreationBody());

    expect($billet)->toBeInstanceOf(Response::class);
});

it('should not be able to cast response to unacceptable cast', function (string $cast) {
    $this->expectException(ResponseCastNotAllowed::class);
    $this->expectExceptionMessage("The response cast: $cast is not allowed");

    $result = [
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
    ];

    fakeBilletResponse(CreateBillet::END_POINT, 'create_request', $result);

    (new PagHiper())->billet(cast: $cast)->create(fakeBilletCreationBody());
})->with([
    ['foobar'],
    ['blabla'],
    ['qwerty'],
]);

it('should be able to throw exception due response reject', function () {
    $this->expectException(PagHiperRejectException::class);
    $this->expectExceptionMessage("transaction_id não informada ou inválida");

    $result = [
        'result'           => 'reject',
        'response_message' => 'transaction_id não informada ou inválida',
    ];

    fakeBilletResponse(CreateBillet::END_POINT, 'create_request', $result);

    (new PagHiper())->billet()->create(fakeBilletCreationBody());
});
