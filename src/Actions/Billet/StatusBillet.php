<?php

namespace DevAjMeireles\PagHiper\Actions\Billet;

use DevAjMeireles\PagHiper\Exceptions\PagHiperRejectException;
use DevAjMeireles\PagHiper\Request;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Client\Response;

class StatusBillet
{
    public const END_POINT = 'transaction/status/';

    /** @throws PagHiperRejectException */
    public static function execute(string|Model $transaction): Response
    {
        $transaction = (new StatusBillet())->parse($transaction);
        $response    = Request::execute(self::END_POINT, ['transaction_id' => $transaction]);

        if ($response->json('status_request.result') === 'reject') {
            throw new PagHiperRejectException($response->json('status_request.response_message'));
        }

        return $response;
    }

    private function parse(string|Model $transaction): string
    {
        if ($transaction instanceof Model) {
            if (property_exists($transaction, 'transaction_id')) {
                return $transaction->transaction_id;
            }

            if (property_exists($transaction, 'transaction')) {
                return $transaction->transaction;
            }
        }

        return $transaction;
    }
}
