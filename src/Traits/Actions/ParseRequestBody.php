<?php

namespace DevAjMeireles\PagHiper\Traits\Actions;

use DevAjMeireles\PagHiper\Actions\Billet\HighOrderBilletCreation\HighOrderCreateBillet;
use DevAjMeireles\PagHiper\DTO\Objects\Billet\Basic as BasicBillet;
use DevAjMeireles\PagHiper\DTO\Objects\Pix\Basic as BasicPix;
use DevAjMeireles\PagHiper\DTO\Objects\{Item, Payer};
use Illuminate\Database\Eloquent\Model;

trait ParseRequestBody
{
    private function parse(BasicPix|BasicBillet $basic, Payer|Model $payer, array|Item $items): array
    {
        $model = $payer instanceof Model;

        $billet          = $basic->toArray();
        $billet['items'] = [];

        //TODO: pix não usa o address.. teremos erro aqui?????

        $billet += $model
            ? (new HighOrderCreateBillet($payer))->execute()
            : $payer->toArray();

        $billet['order_id'] = $model
            ? sprintf('%s|%s:%s', $billet['order_id'], get_class($payer), $payer->id)
            : $billet['order_id'];

        if ($items instanceof Item) {
            $billet['items'] += [[...$items->toArray()]];
        } else {
            foreach ($items as $item) {
                $billet['items'][] = [...$item->toArray()];
            }
        }

        return $billet;
    }
}
