<?php

use DevAjMeireles\PagHiper\Core\Request\Request;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

uses(TestCase::class)->in(__DIR__);

function fakeBilletCreationBody(): array
{
    $fake = fake('pt_BR');

    return [
        'apiKey'           => $fake->uuid(),
        'order_id'         => $fake->randomDigit(),
        'payer_email'      => $fake->email(),
        'payer_name'       => $fake->name(),
        'payer_cpf_cnpj'   => $fake->cpf(false),
        'payer_phone'      => $fake->cellphone(false),
        'notification_url' => $fake->url(),
        'days_due_date'    => $fake->numberBetween(3, 10),
        'type_bank_slip'   => 'boletoA4',
        // address
        'payer_street'     => $fake->streetName(),
        'payer_number'     => $fake->randomDigit(),
        'payer_complement' => 'Home',
        'payer_district'   => $fake->word(),
        'payer_city'       => $fake->city(),
        'payer_zip_code'   => $fake->postcode(),
        // item
        'items' => [
            [
                'description' => $fake->sentence(),
                'quantity'    => 1,
                'item_id'     => 1,
                'price_cents' => 5000,
            ],
        ],
    ];
}

function fakeBilletResponse(string $path, string $index, array $data): void
{
    Http::fake([
        Request::url($path) => Http::response([$index => $data]),
    ]);
}
