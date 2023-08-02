<?php

namespace DevAjMeireles\PagHiper\Actions\Billet;

use DevAjMeireles\PagHiper\Exceptions\PagHiperRejectException;
use DevAjMeireles\PagHiper\Request;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Client\Response;

class CancelBillet
{
    public const END_POINT = 'transaction/cancel/';

    /** @throws PagHiperRejectException */
    public static function execute(string|Model $transaction): Response
    {
        $response = Request::resource('billet')
            ->execute(self::END_POINT, [
                'status'         => 'canceled',
                'transaction_id' => $transaction,
            ]);

        if ($response->json('cancellation_request.result') === 'reject') {
            throw new PagHiperRejectException($response->json('cancellation_request.response_message'));
        }

        return $response;
    }
}
