<?php

namespace DevAjMeireles\PagHiper;

use DevAjMeireles\PagHiper\Billet\Billet;

class PagHiper
{
    public function billet(): Billet
    {
        return new Billet();
    }
}
