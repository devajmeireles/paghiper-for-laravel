<?php

use DevAjMeireles\PagHiper\DTO\Objects\{Address, Basic};
use DevAjMeireles\PagHiper\DTO\Objects\{Item};
use DevAjMeireles\PagHiper\DTO\Objects\{Payer};
use DevAjMeireles\PagHiper\Request;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

uses(TestCase::class)->in(__DIR__);

function fakeBilletCreationBody(): array
{
    $fake = fake('pt_BR');

    $payerObject   = new Payer($fake->name(), $fake->email(), $fake->cpf(false), $fake->cellphone(false));
    $basicObject   = new Basic($fake->randomDigit(), $fake->url());
    $addressObject = new Address($fake->streetName(), $fake->randomDigit(), $fake->word(), $fake->city(), $fake->word(), $fake->country(), $fake->postcode());
    $itemObject    = new Item($fake->randomDigit(), $fake->word(), $fake->randomDigit(), 1500);

    return [$payerObject, $basicObject, $addressObject, $itemObject];
}

function fakeBilletResponse(string $path, string $index, array $data): void
{
    Http::fake([
        Request::url($path) => Http::response([$index => $data]),
    ]);
}
