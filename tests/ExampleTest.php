<?php

use DevAjMeireles\PagHiper\Billet;
use DevAjMeireles\PagHiper\PagHiper;

it('can test', function () {
    $billet = Billet::factory()->create();

    expect(true)->toBeTrue();
});
