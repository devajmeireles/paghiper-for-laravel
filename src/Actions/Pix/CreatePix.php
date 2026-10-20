<?php

namespace DevAjMeireles\PagHiper\Actions\Pix;

use DevAjMeireles\PagHiper\DTO\Objects\{Basic, Item, Payer};
use DevAjMeireles\PagHiper\Exceptions\{PagHiperRejectException, UnallowedEmptyNotificationUrl, WrongModelSetUpException};
use DevAjMeireles\PagHiper\Request;
use DevAjMeireles\PagHiper\Traits\ParseRequestBody;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Client\Response;

class CreatePix
{
    use ParseRequestBody;

    public const END_POINT = 'invoice/create/';

    /** @throws PagHiperRejectException|UnallowedEmptyNotificationUrl|WrongModelSetUpException */
    public static function execute(Basic $basic, Payer|Model $payer, array|Item $items): Response
    {
        $body = (new self())->parse($basic, $payer, $items);

        // PagHiper ignores minutes_due_date unless days_due_date is 0.
        if ($body['minutes_due_date'] !== null) {
            $body['days_due_date'] = 0;
        }

        $response = Request::resource('pix')
            ->execute(self::END_POINT, $body);

        if ($response->json('pix_create_request.result') === 'reject') {
            throw new PagHiperRejectException($response->json('pix_create_request.response_message'));
        }

        return $response;
    }
}
