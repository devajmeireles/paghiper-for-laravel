<?php

use DevAjMeireles\PagHiper\Actions\Billet\CancelBillet;
use DevAjMeireles\PagHiper\Enums\Cast;
use DevAjMeireles\PagHiper\Exceptions\PagHiperRejectException;
use DevAjMeireles\PagHiper\PagHiper;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Collection;

$model = new class () extends Model {
    protected string $transaction = 'BPV661O7AVLORCN5';
};

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

    $status = (new PagHiper())->billet(Cast::Collection)->cancel($transaction);

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

    $status = (new PagHiper())->billet(Cast::Response)->cancel($transaction);

    expect($status)->toBeInstanceOf(Response::class);
});

it('should be able to consult billet status using model object', function () use ($model) {
    $transaction = $model->transaction;

    $result = [
        'result'           => 'success',
        'response_message' => "O Boleto $transaction foi cancelado com Sucesso",
        'http_code'        => '200',
    ];

    fakeBilletResponse(CancelBillet::END_POINT, 'cancellation_request', $result);

    $status = (new PagHiper())->billet(Cast::Response)->cancel($model);

    expect($status)
        ->toBeInstanceOf(Response::class)
        ->and($status->json('cancellation_request.response_message'))
        ->toContain($model->transaction);
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
