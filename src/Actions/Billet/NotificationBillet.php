<?php

namespace DevAjMeireles\PagHiper\Actions\Billet;

use DevAjMeireles\PagHiper\Exceptions\PagHiperRejectException;
use DevAjMeireles\PagHiper\Request;
use Illuminate\Http\Client\Response;

class NotificationBillet
{
    public const END_POINT = 'transaction/notification/';

    /** @throws PagHiperRejectException */
    public static function execute(string $notification, string $transaction): Response
    {
        $response = Request::resource('billet')
            ->execute(self::END_POINT, [
                'notification_id' => $notification,
                'transaction_id'  => $transaction,
            ]);

        if ($response->json('status_request.result') === 'reject') {
            throw new PagHiperRejectException($response->json('status_request.response_message'));
        }

        return $response;
    }
}
