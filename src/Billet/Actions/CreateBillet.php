<?php

namespace DevAjMeireles\PagHiper\Billet\Actions;

use DevAjMeireles\PagHiper\Billet\Actions\HighOrderInteraction\HighOrderCreateBillet;
use DevAjMeireles\PagHiper\Core\Exceptions\PagHiperRejectException;
use DevAjMeireles\PagHiper\Core\Request\Request;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Client\Response;

class CreateBillet
{
    public const END_POINT = 'transaction/create/';

    /** @throws PagHiperRejectException */
    public static function execute(array|Model $data, array $parameters = []): Response
    {
        $data = $data instanceof Model
            ? (new HighOrderCreateBillet($data))->execute()
            : $data;

        $response = Request::execute(self::END_POINT, array_merge($data, $parameters));

        if ($response->json('create_request.result') === 'reject') {
            throw new PagHiperRejectException($response->json('create_request.response_message'));
        }

        return $response;
    }
}
