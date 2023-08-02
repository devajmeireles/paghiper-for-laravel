<?php

namespace DevAjMeireles\PagHiper\Actions\Pix;

use DevAjMeireles\PagHiper\Exceptions\PagHiperRejectException;
use DevAjMeireles\PagHiper\Request;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Client\Response;

class StatusPix
{
    public const END_POINT = 'invoice/status/';

    /** @throws PagHiperRejectException */
    public static function execute(string|Model $transaction): Response
    {
        $response = Request::resource('pix')
            ->execute(self::END_POINT, [
                'transaction_id' => $transaction,
            ]);

        if ($response->json('status_request.result') === 'reject') {
            throw new PagHiperRejectException($response->json('status_request.response_message'));
        }

        return $response;
    }
}
