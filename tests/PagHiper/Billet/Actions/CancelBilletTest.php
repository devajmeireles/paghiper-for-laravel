<?php

use DevAjMeireles\PagHiper\Billet\Actions\CancelBillet;
use DevAjMeireles\PagHiper\Core\Request\Request;
use DevAjMeireles\PagHiper\PagHiper;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;

describe('cancel billet', function () {

    it('should be able to cancel billet casting to array', function () {
        $transaction = 'BPV661O7AVLORCN5';

        $result = [
            'result'           => 'success',
            'response_message' => "O Boleto $transaction foi cancelado com Sucesso",
            'http_code'        => '200',
        ];

        Http::fake([
            Request::url(CancelBillet::END_POINT) => Http::response([
                'cancellation_request' => $result,
            ]),
        ]);

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

        Http::fake([
            Request::url(CancelBillet::END_POINT) => Http::response([
                'cancellation_request' => $result,
            ]),
        ]);

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

        Http::fake([
            Request::url(CancelBillet::END_POINT) => Http::response([
                'cancellation_request' => $result,
            ]),
        ]);

        $status = (new PagHiper())->billet(cast: 'response')->cancel($transaction);

        expect($status)->toBeInstanceOf(Response::class);
    });
});
