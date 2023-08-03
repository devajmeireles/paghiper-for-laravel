<?php

use DevAjMeireles\PagHiper\DTO\Objects\Pix\Basic;

it('should return valid basic instance using make with array', function () {
    expect(Basic::make([
        'order_id'         => '12345678901',
        'notification_url' => $url = fake()->url(),
        'days_due_date'    => 10,
        'discount_cents'   => 20000,
        'minutes_due_date' => 10,
    ])
        ->toArray())
        ->toBeArray()
        ->toBe([
            'order_id'             => '12345678901',
            'notification_url'     => $url,
            'days_due_date'        => 10,
            'minutes_due_date'     => 10,
            'discount_cents'       => 20000,
            'shipping_price_cents' => null,
            'shipping_methods'     => null,
            'partners_id'          => null,
            'number_ntfiscal'      => null,
            'fixed_description'    => null,
            'seller_description'   => null,
        ]);
});

it('should return valid basic instance using make with deconstruction', function () {
    $payer = Basic::make('12345678901', $url = fake()->url(), 5, 10, 1500);

    expect($payer->toArray())->toBeArray()->toBe([
        'order_id'             => '12345678901',
        'notification_url'     => $url,
        'days_due_date'        => 5,
        'minutes_due_date'     => 10,
        'discount_cents'       => 1500,
        'shipping_price_cents' => null,
        'shipping_methods'     => null,
        'partners_id'          => null,
        'number_ntfiscal'      => null,
        'fixed_description'    => null,
        'seller_description'   => null,
    ]);
});

it('should be able to construct basic object using make and set', function () {
    $basic = Basic::make()
        ->set('order_id', '12345678901')
        ->set('notification_url', $url = fake()->url())
        ->set('days_due_date', 55)
        ->set('discount_cents', 2700)
        ->set('minutes_due_date', 10)
        ->set('shipping_price_cents', 123)
        ->set('shipping_methods', 'foo-bar')
        ->set('partners_id', null)
        ->set('number_ntfiscal', null)
        ->set('fixed_description', null)
        ->set('seller_description', null)
        ->set('late_payment_fine', null);

    expect($basic->toArray())->toBeArray()->toBe([
        'order_id'             => '12345678901',
        'notification_url'     => $url,
        'days_due_date'        => 55,
        'minutes_due_date'     => 10,
        'discount_cents'       => 2700,
        'shipping_price_cents' => 123,
        'shipping_methods'     => 'foo-bar',
        'partners_id'          => null,
        'number_ntfiscal'      => null,
        'fixed_description'    => null,
        'seller_description'   => null,
    ]);
});
