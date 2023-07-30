<?php

use DevAjMeireles\PagHiper\DTO\Objects\Payer;

it('should return valid payer instance using make with array', function () {
    expect(Payer::make([
        'name'     => 'John Doe',
        'email'    => 'jhon.doe@gmail.com',
        'document' => '12345678901',
        'phone'    => '11999999999',
    ])
        ->toArray())
        ->toBeArray()
        ->toBe([
            'payer_name'     => 'John Doe',
            'payer_email'    => 'jhon.doe@gmail.com',
            'payer_cpf_cnpj' => '12345678901',
            'payer_phone'    => '11999999999',
        ]);
});

it('should return valid payer instance using make with deconstruction', function () {
    $payer = Payer::make('John Doe', 'jhon.doe@gmail.com', '12345678901', '11999999999');

    expect($payer->toArray())->toBeArray()->toBe([
        'payer_name'     => 'John Doe',
        'payer_email'    => 'jhon.doe@gmail.com',
        'payer_cpf_cnpj' => '12345678901',
        'payer_phone'    => '11999999999',
    ]);
});
