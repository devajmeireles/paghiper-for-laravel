<?php

use DevAjMeireles\PagHiper\PagHiper;

it('can test', function () {
    $paghiper = PagHiper::factory()->create();

    dd($paghiper);

    expect(true)->toBeTrue();
});
