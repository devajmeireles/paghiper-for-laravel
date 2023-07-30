<?php

namespace DevAjMeireles\PagHiper\Contracts;

interface PagHiperModelAbstraction
{
    public function pagHiperName(): string;

    public function pagHiperEmail(): string;

    public function pagHiperPhone(): string;

    public function pagHiperCpfCnpj(): string;

    public function pagHiperAddress(): array;
}
