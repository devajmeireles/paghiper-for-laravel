<?php

namespace DevAjMeireles\PagHiper\Traits;

use DevAjMeireles\PagHiper\Actions\Billet\CreateBillet;
use DevAjMeireles\PagHiper\Actions\Billet\HighOrderBilletCreation\HighOrderCreateBillet;
use DevAjMeireles\PagHiper\DTO\Objects\{Basic, Item, Payer};
use Illuminate\Database\Eloquent\Model;

trait ParseRequestBody
{
    private function parse(Basic $basic, Payer|Model $payer, array|Item $items): array
    {
        $model = $payer instanceof Model;

        $body          = $basic->toArray();
        $body['items'] = [];

        $body += $model
            ? (new HighOrderCreateBillet($payer))->execute()
            : $payer->toArray($this instanceof CreateBillet);

        $body['order_id'] = $model
            ? sprintf('%s|%s:%s', $body['order_id'], get_class($payer), $payer->id)
            : $body['order_id'];

        if ($items instanceof Item) {
            $body['items'] += [[...$items->toArray()]];
        } else {
            foreach ($items as $item) {
                $body['items'][] = [...$item->toArray()];
            }
        }

        return $body;
    }
}
