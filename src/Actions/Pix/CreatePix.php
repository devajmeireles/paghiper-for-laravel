<?php

namespace DevAjMeireles\PagHiper\Actions\Pix;

use DevAjMeireles\PagHiper\DTO\Objects\Pix\Basic;
use DevAjMeireles\PagHiper\DTO\Objects\{Item, Payer};
use DevAjMeireles\PagHiper\Exceptions\PagHiperRejectException;
use DevAjMeireles\PagHiper\Request;
use DevAjMeireles\PagHiper\Traits\Actions\ParseRequestBody;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Client\Response;

class CreatePix
{
    use ParseRequestBody;

    public const END_POINT = 'invoice/create/';

    public static function execute(Basic $basic, Payer|Model $payer, array|Item $items): Response
    {
        $response = Request::resource('pix')
            ->execute(self::END_POINT, (new self())->parse($basic, $payer, $items));

        if ($response->json('pix_create_request.result') === 'reject') {
            // TODO: exception pix
            throw new PagHiperRejectException($response->json('pix_create_request.response_message'));
        }

        return $response;
    }
}
