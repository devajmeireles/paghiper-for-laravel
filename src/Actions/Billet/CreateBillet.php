<?php

namespace DevAjMeireles\PagHiper\Actions\Billet;

use DevAjMeireles\PagHiper\Actions\Billet\HighOrderBilletCreation\HighOrderCreateBillet;
use DevAjMeireles\PagHiper\DTO\Objects\{Address, Basic};
use DevAjMeireles\PagHiper\DTO\Objects\{Item};
use DevAjMeireles\PagHiper\DTO\Objects\{Payer};
use DevAjMeireles\PagHiper\Exceptions\{PagHiperRejectException};
use DevAjMeireles\PagHiper\Request;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Client\Response;

class CreateBillet
{
    public const END_POINT = 'transaction/create/';

    /** @throws PagHiperRejectException */
    public static function execute(Payer|Model $payer, Basic $basic, Address $address, array|Item $items): Response
    {
        $response = Request::execute(self::END_POINT, (new self())->parse($payer, $basic, $address, $items));

        if ($response->json('create_request.result') === 'reject') {
            throw new PagHiperRejectException($response->json('create_request.response_message'));
        }

        return $response;
    }

    private function parse(Payer|Model $payer, Basic $basic, Address $address, array|Item $items): array
    {
        $billet          = $basic->toArray();
        $billet['items'] = [];

        $billet += $payer instanceof Model
            ? (new HighOrderCreateBillet($payer))->execute()
            : array_merge($payer->toArray(), $address->toArray());

        if ($items instanceof Item) {
            $billet['items'] += [[...$items->toArray()]];
        } else {
            foreach ($items as $item) {
                $billet['items'] += [[...$item->toArray()]];
            }
        }

        return $billet;
    }
}
