<?php

use DevAjMeireles\PagHiper\DTO\Objects\{Billet\Address, Billet\Payer};

it('should return valid payer instance using make with array', function () {
    expect(Payer::make([
        'name'     => 'John Doe',
        'email'    => 'jhon.doe@gmail.com',
        'cpf_cnpj' => '12345678901',
        'phone'    => '11999999999',
        'address'  => $address = new Address('Jhon Street', 123, 'House', 'Bah', 'Nashville', 'US', '12345678'),
    ])
        ->toArray())
        ->toBeArray()
        ->toBe([
            'payer_name'     => 'John Doe',
            'payer_email'    => 'jhon.doe@gmail.com',
            'payer_cpf_cnpj' => '12345678901',
            'payer_phone'    => '11999999999',
            ...$address->toArray(),
        ]);
});

it('should return valid payer instance using make with deconstruction', function () {
    $payer = Payer::make(
        'John Doe',
        'jhon.doe@gmail.com',
        '12345678901',
        '11999999999',
        $address = new Address('Jhon Street', 123, 'House', 'Bah', 'Nashville', 'US', '12345678')
    );

    expect($payer->toArray())->toBeArray()->toBe([
        'payer_name'     => 'John Doe',
        'payer_email'    => 'jhon.doe@gmail.com',
        'payer_cpf_cnpj' => '12345678901',
        'payer_phone'    => '11999999999',
        ...$address->toArray(),
    ]);
});

it('should be able to construct payer object using make and set', function () {
    $payer = Payer::make()
        ->set('name', 'Jhon Doe')
        ->set('email', $url = fake()->email())
        ->set('cpf_cnpj', '12345678901')
        ->set('phone', $phone = '11999999999')
        ->set(
            'address',
            $address = Address::make()
                ->set('street', 'Jhon Street')
                ->set('number', 123)
                ->set('complement', 'House')
                ->set('district', 'Bah')
                ->set('city', 'Nashville')
                ->set('state', 'US')
                ->set('zip_code', '12345678')
        );

    expect($payer->toArray())->toBeArray()->toBe([
        'payer_name'     => 'Jhon Doe',
        'payer_email'    => $url,
        'payer_cpf_cnpj' => '12345678901',
        'payer_phone'    => $phone,
        ...$address->toArray(),
    ]);
});
