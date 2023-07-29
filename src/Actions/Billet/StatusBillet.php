<?php

namespace DevAjMeireles\PagHiper\Actions\Billet;

use DevAjMeireles\PagHiper\Exceptions\PagHiperRejectException;
use DevAjMeireles\PagHiper\Request;
use Illuminate\Http\Client\Response;

class StatusBillet
{
    public const END_POINT = 'transaction/status/';

    /** @throws PagHiperRejectException */
    public static function execute(string $transaction): Response
    {
        $response = Request::execute(self::END_POINT, ['transaction_id' => $transaction]);

        if ($response->json('status_request.result') === 'reject') {
            throw new PagHiperRejectException($response->json('status_request.response_message'));
        }

        return $response;
    }
}
