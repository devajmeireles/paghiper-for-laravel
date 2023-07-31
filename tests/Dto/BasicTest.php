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
            'order_id'                      => '12345678901',
            'notification_url'              => $url,
            'days_due_date'                 => 10,
            'type_bank_slip'                => 'foo-bar',
            'discount_cents'                => 20000,
            'shipping_price_cents'          => null,
            'shipping_methods'              => null,
            'partners_id'                   => null,
            'number_ntfiscal'               => null,
            'fixed_description'             => null,
            'seller_description'            => null,
            'late_payment_fine'             => null,
            'per_day_interest'              => null,
            'early_payment_discounts_days'  => null,
            'early_payment_discounts_cents' => null,
            'open_after_day_due'            => null,
        ]);
});

it('should return valid basic instance using make with deconstruction', function () {
    $payer = Basic::make('12345678901', $url = fake()->url(), 5, 'foo-bar', 1500);

    expect($payer->toArray())->toBeArray()->toBe([
        'order_id'                      => '12345678901',
        'notification_url'              => $url,
        'days_due_date'                 => 5,
        'type_bank_slip'                => 'foo-bar',
        'discount_cents'                => 1500,
        'shipping_price_cents'          => null,
        'shipping_methods'              => null,
        'partners_id'                   => null,
        'number_ntfiscal'               => null,
        'fixed_description'             => null,
        'seller_description'            => null,
        'late_payment_fine'             => null,
        'per_day_interest'              => null,
        'early_payment_discounts_days'  => null,
        'early_payment_discounts_cents' => null,
        'open_after_day_due'            => null,
    ]);
});

it('should be able to construct basic object using make and set', function () {
    $basic = Basic::make()
        ->set('order_id', '12345678901')
        ->set('notification_url', $url = fake()->url())
        ->set('days_due_date', 55)
        ->set('type_bank_slip', 'foo-bar-baz')
        ->set('discount_cents', 2700)
        ->set('shipping_price_cents', 123)
        ->set('shipping_methods', 'foo-bar')
        ->set('open_after_day_due', 144);

    expect($basic->toArray())->toBeArray()->toBe([
        'order_id'                      => '12345678901',
        'notification_url'              => $url,
        'days_due_date'                 => 55,
        'type_bank_slip'                => 'foo-bar-baz',
        'discount_cents'                => 2700,
        'shipping_price_cents'          => 123,
        'shipping_methods'              => 'foo-bar',
        'partners_id'                   => null,
        'number_ntfiscal'               => null,
        'fixed_description'             => null,
        'seller_description'            => null,
        'late_payment_fine'             => null,
        'per_day_interest'              => null,
        'early_payment_discounts_days'  => null,
        'early_payment_discounts_cents' => null,
        'open_after_day_due'            => 144,
    ]);
});
