<?php

namespace DevAjMeireles\PagHiper\Actions\Pix;

use DevAjMeireles\PagHiper\Exceptions\PagHiperRejectException;
use DevAjMeireles\PagHiper\Request as PagHiperRequest;
use Illuminate\Http\Client\Response;
use Illuminate\Http\Request;

class NotificationPix
{
    public const END_POINT = 'invoice/notification/';

    /** @throws PagHiperRejectException */
    public static function execute(string|Request $notification, string $transaction = null): Response
    {
        $notificationId = $notification instanceof Request
            ? $notification->input('notification_id')
            : $notification;

        $transactionId = $notification instanceof Request
            ? $notification->input('transaction_id')
            : $transaction;

        $response = PagHiperRequest::resource('pix')
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
