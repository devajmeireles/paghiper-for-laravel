<?php

use DevAjMeireles\PagHiper\DTO\Objects\Billet\Item;

it('should return valid items instance using make with array', function () {
    expect(Item::make([
        'item_id'     => '123',
        'description' => 'Foo Bar',
        'quantity'    => 1,
        'price_cents' => 1500,
    ])
        ->toArray())
        ->toBeArray()
        ->toBe([
            'item_id'     => '123',
            'description' => 'Foo Bar',
            'quantity'    => 1,
            'price_cents' => 1500,
        ]);
});

it('should return valid items instance using make with deconstruction', function () {
    $items = Item::make('123', 'Foo Bar', 1, 1500);

    expect($items->toArray())
        ->toBeArray()
        ->toBe([
            'item_id'     => '123',
            'description' => 'Foo Bar',
            'quantity'    => 1,
            'price_cents' => 1500,
        ]);
});

it('should be able to construct item object using make and set', function () {
    $item = Item::make()
        ->set('item_id', '1255')
        ->set('description', 'Foo Bar')
        ->set('quantity', 12)
        ->set('price_cents', 1500);

    expect($item->toArray())->toBeArray()->toBe([
        'item_id'     => '1255',
        'description' => 'Foo Bar',
        'quantity'    => 12,
        'price_cents' => 1500,
    ]);
});
