<?php

use DevAjMeireles\PagHiper\DTO\Objects\Basic;

it('should return valid basic instance using make with array', function () {
    expect(Basic::make([
        'order_id'         => '12345678901',
        'notification_url' => $url = fake()->url(),
        'days_due_date'     => 2,
        'type_bank_slip'    => 'boletoA4',
        'discount_cents'   => 0,
    ])
        ->toArray())
        ->toBeArray()
        ->toBe([
            'order_id'         => '12345678901',
            'days_due_date'    => 2,
            'type_bank_slip'   => 'boletoA4',
            'notification_url' => $url,
            'discount_cents'   => 0,
        ]);
});

it('should return valid basic instance using make with deconstruction', function () {
    $payer = Basic::make('12345678901', $url = fake()->url());

    expect($payer->toArray())->toBeArray()->toBe([
        'order_id'         => '12345678901',
        'days_due_date'    => 2,
        'type_bank_slip'   => 'boletoA4',
        'notification_url' => $url,
        'discount_cents'   => 0,
    ]);
});
