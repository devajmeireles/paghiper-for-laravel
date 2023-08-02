<?php

use DevAjMeireles\PagHiper\DTO\Objects\{Billet\Address, Billet\Basic};
use DevAjMeireles\PagHiper\DTO\Objects\{Item};
use DevAjMeireles\PagHiper\DTO\Objects\{Payer};
use DevAjMeireles\PagHiper\Request;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

uses(TestCase::class)->in(__DIR__);

function fakeBilletCreationBody(): array
{
    $fake = fake('pt_BR');

    $payer = Payer::make()
        ->set('name', $fake->name())
        ->set('email', fake()->email())
        ->set('cpf_cnpj', $fake->cpf(false))
        ->set('phone', $phone = $fake->cellphone(false))
        ->set(
            'address',
            Address::make()
                ->set('street', $fake->streetName())
                ->set('number', $fake->randomDigit())
                ->set('complement', $fake->word())
                ->set('district', $fake->word())
                ->set('city', $fake->city())
                ->set('state', $fake->country())
                ->set('zip_code', $fake->postcode())
        );

    $basic = Basic::make()
        ->set('order_id', $fake->randomDigit())
        ->set('notification_url', fake()->url())
        ->set('days_due_date', $fake->randomDigit())
        ->set('type_bank_slip', $fake->word())
        ->set('discount_cents', $fake->numerify('####'));

    $item = Item::make()
        ->set('item_id', $fake->randomDigit())
        ->set('description', $fake->word())
        ->set('quantity', $fake->randomDigit())
        ->set('price_cents', $fake->numerify('####'));

    return [$basic, $payer, $item];
}

function fakeBilletResponse(string $path, string $index, array $data): void
{
    Http::fake([
        Request::resource('billet')->url($path) => Http::response([$index => $data]),
    ]);
}
