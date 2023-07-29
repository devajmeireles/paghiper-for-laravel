<?php

namespace DevAjMeireles\PagHiper\Core\Contracts;

interface PagHiperModelAbstraction
{
    public function pagHiperName(): string;

    public function pagHiperEmail(): string;

    public function pagHiperPhone(): string;

    public function pagHiperDocument(): string;

    public function pagHiperAddress(): array;
}
