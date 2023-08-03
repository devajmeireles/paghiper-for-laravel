<?php

use DevAjMeireles\PagHiper\Actions\Pix\CancelPix;
use DevAjMeireles\PagHiper\Enums\Cast;
use DevAjMeireles\PagHiper\Exceptions\PagHiperRejectException;
use DevAjMeireles\PagHiper\PagHiper;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Collection;

it('should be able to cancel pix casting to array', function () {
    $transaction = 'BPV661O7AVLORCN5';

    $result = [
        "result"           => "success",
        "response_message" => "O Pix $transaction foi cancelado com Sucesso",
        "http_code"        => "200",
    ];

    fakePixResponse(CancelPix::END_POINT, 'cancellation_request', $result);

    $cancel = (new PagHiper())->pix()->cancel($transaction);

    expect($cancel)
        ->toBeArray()
        ->and($cancel)
        ->toBe($result);
});

it('should be able to cancel pix casting to json', function () {
    $transaction = 'BPV661O7AVLORCN5';

    $result = [
        "result"           => "success",
        "response_message" => "O Pix $transaction foi cancelado com Sucesso",
        "http_code"        => "200",
    ];

    fakePixResponse(CancelPix::END_POINT, 'cancellation_request', $result);

    $cancel = (new PagHiper())->pix(Cast::Json)->cancel($transaction);

    expect($cancel)
        ->toBeJson()
        ->and($cancel)
        ->toBe(collect($result)->toJson());
});

it('should be able to cancel pix casting to collection', function (Cast $cast) {
    $transaction = 'BPV661O7AVLORCN5';

    $result = [
        "result"           => "success",
        "response_message" => "O Pix $transaction foi cancelado com Sucesso",
        "http_code"        => "200",
    ];

    fakePixResponse(CancelPix::END_POINT, 'cancellation_request', $result);

    $cancel = (new PagHiper())->pix($cast)->cancel($transaction);

    expect($cancel)
        ->toBeInstanceOf(Collection::class)
        ->and($cancel->get('response_message'))
        ->toBe("O Pix $transaction foi cancelado com Sucesso");
})->with([
    Cast::Collect,
    Cast::Collection,
]);

it('should be able to cancel pix casting to original response', function () {
    $transaction = 'BPV661O7AVLORCN5';

    $result = [
        "result"           => "success",
        "response_message" => "O Pix $transaction foi cancelado com Sucesso",
        "http_code"        => "200",
    ];

    fakePixResponse(CancelPix::END_POINT, 'cancellation_request', $result);

    $cancel = (new PagHiper())->pix(Cast::Response)->cancel($transaction);

    expect($cancel)
        ->toBeInstanceOf(Response::class)
        ->and($cancel->json('cancellation_request.response_message'))
        ->toBe("O Pix $transaction foi cancelado com Sucesso");
});

it('should be able to throw exception due response reject', function () {
    $this->expectException(PagHiperRejectException::class);
    $this->expectExceptionMessage("token ou apiKey inválidos");

    $result = [
        'result'           => 'reject',
        'response_message' => 'token ou apiKey inválidos',
    ];

    fakePixResponse(CancelPix::END_POINT, 'cancellation_request', $result);

    (new PagHiper())->pix()->cancel('BPV661O7AVLORCN5');
});
