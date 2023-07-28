<?php

namespace DevAjMeireles\PagHiper\Billet\Actions;

use DevAjMeireles\PagHiper\Core\Exceptions\PagHiperRejectException;
use DevAjMeireles\PagHiper\Core\Request\Request;
use Illuminate\Http\Client\Response;

class ConsultBilletStatus
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
