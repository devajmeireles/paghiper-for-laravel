<?php

namespace DevAjMeireles\PagHiper\Actions\Billet;

use DevAjMeireles\PagHiper\Exceptions\PagHiperRejectException;
use DevAjMeireles\PagHiper\Request as PagHiperRequest;
use Illuminate\Http\Client\Response;
use Illuminate\Http\Request;

class NotificationBillet
{
    public const END_POINT = 'transaction/notification/';

    /** @throws PagHiperRejectException */
    public static function execute(string|Request $notification, string $transaction = null): Response
    {
        $notificationId = $notification instanceof Request
            ? $notification->input('notification_id')
            : $notification;

        $transactionId = $notification instanceof Request
            ? $notification->input('transaction_id')
            : $transaction;

        $response = PagHiperRequest::resource('billet')
            ->execute(self::END_POINT, [
                'notification_id' => $notificationId,
                'transaction_id'  => $transactionId,
            ]);

        if ($response->json('status_request.result') === 'reject') {
            throw new PagHiperRejectException($response->json('status_request.response_message'));
        }

        return $response;
    }
}
