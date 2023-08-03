<?php

namespace DevAjMeireles\PagHiper\Actions\Billet;

use DevAjMeireles\PagHiper\DTO\Objects\{Basic, Item, Payer};
use DevAjMeireles\PagHiper\Exceptions\{PagHiperRejectException, UnallowedEmptyNotificationUrl, WrongModelSetUpException};
use DevAjMeireles\PagHiper\Request;
use DevAjMeireles\PagHiper\Traits\ParseRequestBody;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Client\Response;

class CreateBillet
{
    use ParseRequestBody;

    public const END_POINT = 'transaction/create/';

    /** @throws PagHiperRejectException|UnallowedEmptyNotificationUrl|WrongModelSetUpException */
    public static function execute(Basic $basic, Payer|Model $payer, array|Item $items): Response
    {
        $response = Request::resource('billet')
            ->execute(self::END_POINT, (new self())->parse($basic, $payer, $items));

        if ($response->json('create_request.result') === 'reject') {
            throw new PagHiperRejectException($response->json('create_request.response_message'));
        }

        return $response;
    }
}
