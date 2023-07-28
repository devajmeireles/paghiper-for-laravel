<?php

namespace DevAjMeireles\PagHiper\Billet\Actions;

use DevAjMeireles\PagHiper\Core\Exceptions\PagHiperRejectException;
use DevAjMeireles\PagHiper\Core\Request\Request;
use Illuminate\Http\Client\Response;

class CreateBillet
{
    public const END_POINT = 'transaction/create/';

    /** @throws PagHiperRejectException */
    public static function execute(array $data): Response
    {
        $response = Request::execute(self::END_POINT, $data);

        if ($response->json('create_request.result') === 'reject') {
            throw new PagHiperRejectException($response->json('create_request.response_message'));
        }

        return $response;
    }
}