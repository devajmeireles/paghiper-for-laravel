<?php

use DevAjMeireles\PagHiper\Billet\Actions\CancelBillet;
use DevAjMeireles\PagHiper\Core\Exceptions\{PagHiperRejectException, ResponseCastNotAllowed};
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

    $status = (new PagHiper())->billet()->cancel($transaction);

    expect($status)
        ->toBeArray()
        ->and($status)
        ->toBe($result);
});

it('should be able to cancel billet casting to collection', function (string $cast) {
    $transaction = 'BPV661O7AVLORCN5';

    $result = [
        'result'           => 'success',
        'response_message' => "O Boleto $transaction foi cancelado com Sucesso",
        'http_code'        => '200',
    ];

    fakeBilletResponse(CancelBillet::END_POINT, 'cancellation_request', $result);

    $status = (new PagHiper())->billet($cast)->cancel($transaction);

    expect($status)->toBeInstanceOf(Collection::class);
})->with(['collection', 'collect']);

it('should be able to cancel billet casting to original response', function () {
    $transaction = 'BPV661O7AVLORCN5';

    $result = [
        'result'           => 'success',
        'response_message' => "O Boleto $transaction foi cancelado com Sucesso",
        'http_code'        => '200',
    ];

    fakeBilletResponse(CancelBillet::END_POINT, 'cancellation_request', $result);

    $status = (new PagHiper())->billet(cast: 'response')->cancel($transaction);

    expect($status)->toBeInstanceOf(Response::class);
});

it('should not be able to cast response to unacceptable cast', function (string $cast) {
    $this->expectException(ResponseCastNotAllowed::class);
    $this->expectExceptionMessage("The response cast: $cast is not allowed");

    $transaction = 'BPV661O7AVLORCN5';

    $result = [
        'result'           => 'success',
        'response_message' => "O Boleto $transaction foi cancelado com Sucesso",
        'http_code'        => '200',
    ];

    fakeBilletResponse(CancelBillet::END_POINT, 'cancellation_request', $result);

    (new PagHiper())->billet(cast: $cast)->cancel($transaction);
})->with([
    ['foobar'],
    ['blabla'],
    ['qwerty'],
]);

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
