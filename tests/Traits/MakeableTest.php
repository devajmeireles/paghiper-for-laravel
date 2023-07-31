<?php

use DevAjMeireles\PagHiper\DTO\Objects\Billet\Item;

it('should only be able to set existent property value', function () {
    $item = Item::make()
        ->set('item_id', '12345')
        ->set('description', 'Foo Bar')
        ->set('quantity', 1)
        ->set('price_cents', 1250)
        ->set('foo_bar', 12)
        ->set('baz_bah', 'baz_bah');

    expect($item->toArray())->toBe([
        'item_id'     => '12345',
        'description' => 'Foo Bar',
        'quantity'    => 1,
        'price_cents' => 1250,
    ])
        ->and($item)
        ->not()
        ->toHaveProperty('foo_bar', 12)
        ->and($item)
        ->not()
        ->toHaveProperty('baz_bah', 'baz_bah');
});
