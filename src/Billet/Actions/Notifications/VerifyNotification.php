<?php

namespace DevAjMeireles\PagHiper\Billet\Actions\Notifications;

use DevAjMeireles\PagHiper\Core\Exceptions\PagHiperRejectException;
use DevAjMeireles\PagHiper\Core\Request\Request;
use Illuminate\Http\Client\Response;

class VerifyNotification
{
    public const END_POINT = 'transaction/notification/';

    /** @throws PagHiperRejectException */
    public static function execute(string $notification, string $transaction): Response
    {
        $response = Request::execute(self::END_POINT, [
            'notification_id' => $notification,
            'transaction_id' => $transaction,
        ]);

        if ($response->json('status_request.result') === 'reject') {
            throw new PagHiperRejectException($response->json('status_request.response_message'));
        }

        return $response;
    }
}
