<?php

namespace DevAjMeireles\PagHiper\Billet\Actions\Billet;

use DevAjMeireles\PagHiper\Core\Exceptions\PagHiperRejectException;
use DevAjMeireles\PagHiper\Core\Request\Request;
use Illuminate\Http\Client\Response;

class CancelBillet
{
    public const END_POINT = 'transaction/cancel/';

    /** @throws PagHiperRejectException */
    public static function execute(string $transaction): Response
    {
        $response = Request::execute(self::END_POINT, [
            'status'         => 'canceled',
            'transaction_id' => $transaction,
        ]);

        if ($response->json('cancellation_request.result') === 'reject') {
            throw new PagHiperRejectException($response->json('cancellation_request.response_message'));
        }

        return $response;
    }
}
