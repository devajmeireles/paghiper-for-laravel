<?php

namespace DevAjMeireles\PagHiper;

use DevAjMeireles\PagHiper\Billet\Billet;

class PagHiper
{
    public function billet(string $cast = 'json'): Billet
    {
        return new Billet($cast);
    }
}
