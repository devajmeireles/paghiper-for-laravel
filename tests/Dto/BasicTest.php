<?php

use DevAjMeireles\PagHiper\DTO\Objects\Billet\Basic;

it('should return valid basic instance using make with array', function () {
    expect(Basic::make([
        'order_id'         => '12345678901',
        'notification_url' => $url = fake()->url(),
        'days_due_date'    => 10,
        'type_bank_slip'   => 'foo-bar',
        'discount_cents'   => 20000,
    ])
        ->toArray())
        ->toBeArray()
        ->toBe([
            'order_id'         => '12345678901',
            'notification_url' => $url,
            'days_due_date'    => 10,
            'type_bank_slip'   => 'foo-bar',
            'discount_cents'   => 20000,
        ]);
});

it('should return valid basic instance using make with deconstruction', function () {
    $payer = Basic::make('12345678901', $url = fake()->url(), 5, 'foo-bar', 1500);

    expect($payer->toArray())->toBeArray()->toBe([
        'order_id'         => '12345678901',
        'notification_url' => $url,
        'days_due_date'    => 5,
        'type_bank_slip'   => 'foo-bar',
        'discount_cents'   => 1500,
    ]);
});

it('should be able to construct basic object using make and set', function () {
    $basic = Basic::make()
        ->set('order_id', '12345678901')
        ->set('notification_url', $url = fake()->url())
        ->set('days_due_date', 55)
        ->set('type_bank_slip', 'foo-bar-baz')
        ->set('discount_cents', 2700);

    expect($basic->toArray())->toBeArray()->toBe([
        'order_id'         => '12345678901',
        'notification_url' => $url,
        'days_due_date'    => 55,
        'type_bank_slip'   => 'foo-bar-baz',
        'discount_cents'   => 2700,
    ]);
});
