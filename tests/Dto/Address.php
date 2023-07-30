<?php

use DevAjMeireles\PagHiper\DTO\Objects\Address;

it('should return valid address instance using make with array', function () {
    expect(Address::make([
        'street'     => 'Foo Bar',
        'number'     => '123',
        'complement' => 'Apt 123',
        'district'   => 'Foo',
        'city'       => 'Bar',
        'state'      => 'SP',
        'zipCode'    => '12345678',
    ])
        ->toArray())
        ->toBeArray()
        ->toBe([
            'payer_street'     => 'Foo Bar',
            'payer_number'     => '123',
            'payer_complement' => 'Apt 123',
            'payer_district'   => 'Foo',
            'payer_city'       => 'Bar',
            'payer_state'      => 'SP',
            'payer_zip_code'   => '12345678',
        ]);
});

it('should return valid basic instance using make with deconstruction', function () {
    $address = Address::make('Foo Bar', '123', 'Apt 123', 'Foo', 'Bar', 'SP', '12345678');

    expect($address->toArray())
        ->toBeArray()
        ->toBe([
            'payer_street'     => 'Foo Bar',
            'payer_number'     => '123',
            'payer_complement' => 'Apt 123',
            'payer_district'   => 'Foo',
            'payer_city'       => 'Bar',
            'payer_state'      => 'SP',
            'payer_zip_code'   => '12345678',
        ]);
});
