<?php

use DevAjMeireles\PagHiper\Actions\Billet\CancelBillet;
use DevAjMeireles\PagHiper\Enums\Cast;
use DevAjMeireles\PagHiper\Exceptions\PagHiperRejectException;
use DevAjMeireles\PagHiper\PagHiper;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Collection;

it('should be able to cancel billet casting to array', function () {
    $transaction = 'BPV661O7AVLORCN5';

    $result = [
        'result'           => 'success',
        'response_message' => "O Boleto $transaction foi cancelado com Sucesso",
        'http_code'        => '200',
    ];

    fakeBilletResponse(CancelBillet::END_POINT, 'cancellation_request', $result);

    $cancel = (new PagHiper())->billet()->cancel($transaction);

    expect($cancel)
        ->toBeArray()
        ->and($cancel)
        ->toBe($result);
});

it('should be able to cancel billet casting to json', function () {
    $transaction = 'BPV661O7AVLORCN5';

    $result = [
        'result'           => 'success',
        'response_message' => "O Boleto $transaction foi cancelado com Sucesso",
        'http_code'        => '200',
    ];

    fakeBilletResponse(CancelBillet::END_POINT, 'cancellation_request', $result);

    $cancel = (new PagHiper())->billet(Cast::Json)->cancel($transaction);

    expect($cancel)
        ->toBeJson()
        ->and($cancel)
        ->toBe(collect($result)->toJson());
});

it('should be able to cancel billet casting to collection', function (Cast $cast) {
    $transaction = 'BPV661O7AVLORCN5';

    $result = [
        'result'           => 'success',
        'response_message' => "O Boleto $transaction foi cancelado com Sucesso",
        'http_code'        => '200',
    ];

    fakeBilletResponse(CancelBillet::END_POINT, 'cancellation_request', $result);

    $cancel = (new PagHiper())->billet($cast)->cancel($transaction);

    expect($cancel)
        ->toBeInstanceOf(Collection::class)
        ->and($cancel->get('response_message'))
        ->toBe("O Boleto $transaction foi cancelado com Sucesso");
})->with([
    Cast::Collect,
    Cast::Collection,
]);

it('should be able to cancel billet casting to original response', function () {
    $transaction = 'BPV661O7AVLORCN5';

    $result = [
        'result'           => 'success',
        'response_message' => "O Boleto $transaction foi cancelado com Sucesso",
        'http_code'        => '200',
    ];

    fakeBilletResponse(CancelBillet::END_POINT, 'cancellation_request', $result);

    $cancel = (new PagHiper())->billet(Cast::Response)->cancel($transaction);

    expect($cancel)
        ->toBeInstanceOf(Response::class)
        ->and($cancel->json('cancellation_request.response_message'))
        ->toBe("O Boleto $transaction foi cancelado com Sucesso");
});

it('should be able to throw exception due response reject', function () {
    $this->expectException(PagHiperRejectException::class);
    $this->expectExceptionMessage("token ou apiKey inválidos");

    $result = [
        'result'           => 'reject',
        'response_message' => 'token ou apiKey inválidos',
    ];

    fakeBilletResponse(CancelBillet::END_POINT, 'cancellation_request', $result);

    (new PagHiper())->billet()->cancel('BPV661O7AVLORCN5');
});
