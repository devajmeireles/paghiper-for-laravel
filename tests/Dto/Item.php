<?php

use DevAjMeireles\PagHiper\DTO\Objects\Item;

it('should return valid items instance using make with array', function () {
    expect(Item::make([
        'id'          => '123',
        'description' => 'Foo Bar',
        'quantity'    => 1,
        'price'       => 1500,
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
