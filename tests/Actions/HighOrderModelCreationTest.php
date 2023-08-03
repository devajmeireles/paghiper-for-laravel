<?php

use DevAjMeireles\PagHiper\Actions\Billet\CreateBillet;
use DevAjMeireles\PagHiper\Actions\Pix\CreatePix;
use DevAjMeireles\PagHiper\Contracts\PagHiperModelAbstraction;
use DevAjMeireles\PagHiper\DTO\Objects\{Basic, Item, Payer};
use DevAjMeireles\PagHiper\Enums\Cast;
use DevAjMeireles\PagHiper\Facades\PagHiper;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Collection;

$model = new class () extends Model implements PagHiperModelAbstraction {
    public function pagHiperName(): string
    {
        return 'John Doe';
    }

    public function pagHiperEmail(): string
    {
        return 'jhon.doe@gmail.com';
    }

    public function pagHiperCpfCnpj(): string
    {
        return '123.456.789-00';
    }

    public function pagHiperPhone(): string
    {
        return '1199999999';
    }

    public function pagHiperAddress(): array
    {
        return [
            'street'     => 'Foo Street',
            'number'     => 123,
            'complement' => 'Home',
            'district'   => 'Bar District',
            'city'       => 'Foo City',
            'zip_code'   => '12345-678',
        ];
    }
};

//region billet
it('should be able to create billet for model instance casting to array', function () use ($model) {
    $transaction = 'HF97T5SH2ZQNLF1Z';

    $result = [
        'result'           => 'success',
        'response_message' => 'transacao criada',
        'transaction_id'   => $transaction,
        'created_date'     => now()->format('Y-m-d H:i:s'),
        'value_cents'      => 1000,
        'status'           => 'pending',
        'order_id'         => 1,
        'due_date'         => now()->addDays(2)->format('Y-m-d'),
        'bank_slip'        => [
            'digitable_line'           => '34191.76304 03906.270248 61514.190000 9 72330000017012',
            'url_slip'                 => 'https://www.paghiper.com/checkout/boleto/180068c7/HF97T5SH2ZQNLF6Z/30039',
            'url_slip_pdf'             => 'https://www.paghiper.com/checkout/boleto/180068c7/HF97T5SH2ZQNLF6Z/30039/pdf',
            'bar_code_number_to_image' => '34199723300000170121763003906270246151419000',
        ],
    ];

    fakeBilletResponse(CreateBillet::END_POINT, 'create_request', $result);

    /** @var Basic $basic */
    /** @var Payer $payer */
    /** @var Item $items */
    [$basic, $payer, $items] = [...fakeBilletCreationBody()];

    $billet = PagHiper::billet()->create($basic, $payer, $items);

    expect($billet)
        ->toBeArray()
        ->and($billet)
        ->toBe($result)
        ->and($billet['transaction_id'])
        ->toBe($transaction);
});

it('should be able to create billet for model instance casting to json', function () use ($model) {
    $transaction = 'HF97T5SH2ZQNLF1Z';

    $result = [
        'result'           => 'success',
        'response_message' => 'transacao criada',
        'transaction_id'   => $transaction,
        'created_date'     => now()->format('Y-m-d H:i:s'),
        'value_cents'      => 1000,
        'status'           => 'pending',
        'order_id'         => 1,
        'due_date'         => now()->addDays(2)->format('Y-m-d'),
        'bank_slip'        => [
            'digitable_line'           => '34191.76304 03906.270248 61514.190000 9 72330000017012',
            'url_slip'                 => 'https://www.paghiper.com/checkout/boleto/180068c7/HF97T5SH2ZQNLF6Z/30039',
            'url_slip_pdf'             => 'https://www.paghiper.com/checkout/boleto/180068c7/HF97T5SH2ZQNLF6Z/30039/pdf',
            'bar_code_number_to_image' => '34199723300000170121763003906270246151419000',
        ],
    ];

    fakeBilletResponse(CreateBillet::END_POINT, 'create_request', $result);

    /** @var Basic $basic */
    /** @var Payer $payer */
    /** @var Item $items */
    [$basic, $payer, $items] = [...fakeBilletCreationBody()];

    $billet = PagHiper::billet(Cast::Json)->create($basic, $payer, $items);

    expect($billet)
        ->toBeJson()
        ->and($billet)
        ->toBe(collect($result)->toJson())
        ->and(json_decode($billet)->transaction_id)
        ->toBe($transaction);
});

it('should be able to create billet for model instance casting to collection', function (Cast $cast) use ($model) {
    $transaction = 'HF97T5SH2ZQNLF1Z';

    $result = [
        'result'           => 'success',
        'response_message' => 'transacao criada',
        'transaction_id'   => $transaction,
        'created_date'     => now()->format('Y-m-d H:i:s'),
        'value_cents'      => 1000,
        'status'           => 'pending',
        'order_id'         => 1,
        'due_date'         => now()->addDays(2)->format('Y-m-d'),
        'bank_slip'        => [
            'digitable_line'           => '34191.76304 03906.270248 61514.190000 9 72330000017012',
            'url_slip'                 => 'https://www.paghiper.com/checkout/boleto/180068c7/HF97T5SH2ZQNLF6Z/30039',
            'url_slip_pdf'             => 'https://www.paghiper.com/checkout/boleto/180068c7/HF97T5SH2ZQNLF6Z/30039/pdf',
            'bar_code_number_to_image' => '34199723300000170121763003906270246151419000',
        ],
    ];

    fakeBilletResponse(CreateBillet::END_POINT, 'create_request', $result);

    /** @var Basic $basic */
    /** @var Payer $payer */
    /** @var Item $items */
    [$basic, $payer, $items] = [...fakeBilletCreationBody()];

    $billet = PagHiper::billet($cast)->create($basic, $model, $items);

    expect($billet)
        ->toBeInstanceOf(Collection::class)
        ->and($billet->get('transaction_id'))
        ->toBe($transaction)
        ->and($billet->toArray())
        ->toBe($result);
})->with([
    Cast::Collect,
    Cast::Collection,
]);

it('should be able to create billet for model instance casting to original response', function () use ($model) {
    $transaction = 'HF97T5SH2ZQNLF1Z';

    $result = [
        'result'           => 'success',
        'response_message' => 'transacao criada',
        'transaction_id'   => $transaction,
        'created_date'     => now()->format('Y-m-d H:i:s'),
        'value_cents'      => 1000,
        'status'           => 'pending',
        'order_id'         => 1,
        'due_date'         => now()->addDays(2)->format('Y-m-d'),
        'bank_slip'        => [
            'digitable_line'           => '34191.76304 03906.270248 61514.190000 9 72330000017012',
            'url_slip'                 => 'https://www.paghiper.com/checkout/boleto/180068c7/HF97T5SH2ZQNLF6Z/30039',
            'url_slip_pdf'             => 'https://www.paghiper.com/checkout/boleto/180068c7/HF97T5SH2ZQNLF6Z/30039/pdf',
            'bar_code_number_to_image' => '34199723300000170121763003906270246151419000',
        ],
    ];

    fakeBilletResponse(CreateBillet::END_POINT, 'create_request', $result);

    /** @var Basic $basic */
    /** @var Payer $payer */
    /** @var Item $items */
    [$basic, $payer, $items] = [...fakeBilletCreationBody()];

    $billet = PagHiper::billet(Cast::Response)->create($basic, $model, $items);

    expect($billet)
        ->toBeInstanceOf(Response::class)
        ->and($billet->json('create_request.transaction_id'))
        ->toBe($transaction)
        ->and($billet->json('create_request'))
        ->toBe($result);
});
//endregion

//region pix
it('should be able to create pix for model instance casting to array', function () use ($model) {
    $result = [
        "result"           => "success",
        "response_message" => "transacao criada",
        "transaction_id"   => $transaction = "HF97T5SH2ZQNLF6Z",
        "created_date"     => now()->format('Y-m-d H:i:s'),
        "value_cents"      => "18507",
        "status"           => "pending",
        "order_id"         => 1,
        "due_date"         => "2017-07-27",
        "pix_code"         => [
            "qrcode_base64"    => "iVBORw0KGgoAAAANSUhEUgAAAPoAAAD6AQAAAACgl2eQA",
            "qrcode_image_url" => "https://pix.paghiper.com/pixcode/?a5eef1cc3013a9a0063ff657229efde603122f9898b26f7c9e5c7d2cc950fdde990aad6d0693bbc3d61b28a640cc775db1eddc55261462ba514f6a41b7d3268c/39JI4HD3FF7NC27Y/84700950.png",
            "emv"              => "00020101021226770014BR.GOV.BCB.PIX2555api.itau/pix/qr/v1/ce7f9743-6575-485a-86da-a0f56cf136565204000053039865802BR5925PAGHIPER SERV ONLINE EIRE6009SAO PAULO62070503***630409AD",
            "pix_url"          => "https://pix.paghiper.com/qrcode/180068c7/HF97T5SH2ZQNLF6Z/30039",
            "bacen_url"        => "https://pix.bcb.gov.br/qr/MDAwMjAxMDEwMjEyMjY3NzAwMTRCUi5HT1YuQkNCLlBJWDI1NTVhcGkuaXRhdS9waXgvcXIvdjEvY2U3Zjk3NDMtNjU3NS00ODVhLTg2ZGEtYTBmNTZjZjEzNjU2NTIwNDAwMDA1MzAzOTg2NTgwMkJSNTkyNVBBR0hJUEVSIFNFUlYgT05MSU5FIEVJUkU2MDA5U0FPIFBBVUxPNjIwNzA1MDMqKio2MzA0MDlBRA==",
        ],
        "http_code" => "201",
    ];

    fakePixResponse(CreatePix::END_POINT, 'pix_create_request', $result);

    /** @var Basic $basic */
    /** @var Payer $payer */
    /** @var Item $items */
    [$basic, $payer, $items] = [...fakeBilletCreationBody()];

    $pix = PagHiper::pix()->create($basic, $model, $items);

    expect($pix)
        ->toBeArray()
        ->and($pix)
        ->toBe($result)
        ->and($pix['transaction_id'])
        ->toBe($transaction);
});

it('should be able to create pix for model instance casting to json', function () use ($model) {
    $result = [
        "result"           => "success",
        "response_message" => "transacao criada",
        "transaction_id"   => $transaction = "HF97T5SH2ZQNLF6Z",
        "created_date"     => now()->format('Y-m-d H:i:s'),
        "value_cents"      => "18507",
        "status"           => "pending",
        "order_id"         => 1,
        "due_date"         => "2017-07-27",
        "pix_code"         => [
            "qrcode_base64"    => "iVBORw0KGgoAAAANSUhEUgAAAPoAAAD6AQAAAACgl2eQA",
            "qrcode_image_url" => "https://pix.paghiper.com/pixcode/?a5eef1cc3013a9a0063ff657229efde603122f9898b26f7c9e5c7d2cc950fdde990aad6d0693bbc3d61b28a640cc775db1eddc55261462ba514f6a41b7d3268c/39JI4HD3FF7NC27Y/84700950.png",
            "emv"              => "00020101021226770014BR.GOV.BCB.PIX2555api.itau/pix/qr/v1/ce7f9743-6575-485a-86da-a0f56cf136565204000053039865802BR5925PAGHIPER SERV ONLINE EIRE6009SAO PAULO62070503***630409AD",
            "pix_url"          => "https://pix.paghiper.com/qrcode/180068c7/HF97T5SH2ZQNLF6Z/30039",
            "bacen_url"        => "https://pix.bcb.gov.br/qr/MDAwMjAxMDEwMjEyMjY3NzAwMTRCUi5HT1YuQkNCLlBJWDI1NTVhcGkuaXRhdS9waXgvcXIvdjEvY2U3Zjk3NDMtNjU3NS00ODVhLTg2ZGEtYTBmNTZjZjEzNjU2NTIwNDAwMDA1MzAzOTg2NTgwMkJSNTkyNVBBR0hJUEVSIFNFUlYgT05MSU5FIEVJUkU2MDA5U0FPIFBBVUxPNjIwNzA1MDMqKio2MzA0MDlBRA==",
        ],
        "http_code" => "201",
    ];

    fakePixResponse(CreatePix::END_POINT, 'pix_create_request', $result);

    /** @var Basic $basic */
    /** @var Payer $payer */
    /** @var Item $items */
    [$basic, $payer, $items] = [...fakeBilletCreationBody()];

    $pix = PagHiper::pix(Cast::Json)->create($basic, $model, $items);

    expect($pix)
        ->toBeJson()
        ->and(json_decode($pix)->transaction_id)
        ->toBe($transaction);
});

it('should be able to create pix for model instance casting to collection', function (Cast $cast) use ($model) {
    $result = [
        "result"           => "success",
        "response_message" => "transacao criada",
        "transaction_id"   => $transaction = "HF97T5SH2ZQNLF6Z",
        "created_date"     => now()->format('Y-m-d H:i:s'),
        "value_cents"      => "18507",
        "status"           => "pending",
        "order_id"         => 1,
        "due_date"         => "2017-07-27",
        "pix_code"         => [
            "qrcode_base64"    => "iVBORw0KGgoAAAANSUhEUgAAAPoAAAD6AQAAAACgl2eQA",
            "qrcode_image_url" => "https://pix.paghiper.com/pixcode/?a5eef1cc3013a9a0063ff657229efde603122f9898b26f7c9e5c7d2cc950fdde990aad6d0693bbc3d61b28a640cc775db1eddc55261462ba514f6a41b7d3268c/39JI4HD3FF7NC27Y/84700950.png",
            "emv"              => "00020101021226770014BR.GOV.BCB.PIX2555api.itau/pix/qr/v1/ce7f9743-6575-485a-86da-a0f56cf136565204000053039865802BR5925PAGHIPER SERV ONLINE EIRE6009SAO PAULO62070503***630409AD",
            "pix_url"          => "https://pix.paghiper.com/qrcode/180068c7/HF97T5SH2ZQNLF6Z/30039",
            "bacen_url"        => "https://pix.bcb.gov.br/qr/MDAwMjAxMDEwMjEyMjY3NzAwMTRCUi5HT1YuQkNCLlBJWDI1NTVhcGkuaXRhdS9waXgvcXIvdjEvY2U3Zjk3NDMtNjU3NS00ODVhLTg2ZGEtYTBmNTZjZjEzNjU2NTIwNDAwMDA1MzAzOTg2NTgwMkJSNTkyNVBBR0hJUEVSIFNFUlYgT05MSU5FIEVJUkU2MDA5U0FPIFBBVUxPNjIwNzA1MDMqKio2MzA0MDlBRA==",
        ],
        "http_code" => "201",
    ];

    fakePixResponse(CreatePix::END_POINT, 'pix_create_request', $result);

    /** @var Basic $basic */
    /** @var Payer $payer */
    /** @var Item $items */
    [$basic, $payer, $items] = [...fakeBilletCreationBody()];

    $pix = PagHiper::pix($cast)->create($basic, $model, $items);

    expect($pix)
        ->toBeCollection()
        ->and($pix->get('transaction_id'))
        ->toBe($transaction);
})->with([
    Cast::Collect,
    Cast::Collection,
]);

it('should be able to create pix for model instance casting to original response', function () use ($model) {
    $result = [
        "result"           => "success",
        "response_message" => "transacao criada",
        "transaction_id"   => $transaction = "HF97T5SH2ZQNLF6Z",
        "created_date"     => now()->format('Y-m-d H:i:s'),
        "value_cents"      => "18507",
        "status"           => "pending",
        "order_id"         => 1,
        "due_date"         => "2017-07-27",
        "pix_code"         => [
            "qrcode_base64"    => "iVBORw0KGgoAAAANSUhEUgAAAPoAAAD6AQAAAACgl2eQA",
            "qrcode_image_url" => "https://pix.paghiper.com/pixcode/?a5eef1cc3013a9a0063ff657229efde603122f9898b26f7c9e5c7d2cc950fdde990aad6d0693bbc3d61b28a640cc775db1eddc55261462ba514f6a41b7d3268c/39JI4HD3FF7NC27Y/84700950.png",
            "emv"              => "00020101021226770014BR.GOV.BCB.PIX2555api.itau/pix/qr/v1/ce7f9743-6575-485a-86da-a0f56cf136565204000053039865802BR5925PAGHIPER SERV ONLINE EIRE6009SAO PAULO62070503***630409AD",
            "pix_url"          => "https://pix.paghiper.com/qrcode/180068c7/HF97T5SH2ZQNLF6Z/30039",
            "bacen_url"        => "https://pix.bcb.gov.br/qr/MDAwMjAxMDEwMjEyMjY3NzAwMTRCUi5HT1YuQkNCLlBJWDI1NTVhcGkuaXRhdS9waXgvcXIvdjEvY2U3Zjk3NDMtNjU3NS00ODVhLTg2ZGEtYTBmNTZjZjEzNjU2NTIwNDAwMDA1MzAzOTg2NTgwMkJSNTkyNVBBR0hJUEVSIFNFUlYgT05MSU5FIEVJUkU2MDA5U0FPIFBBVUxPNjIwNzA1MDMqKio2MzA0MDlBRA==",
        ],
        "http_code" => "201",
    ];

    fakePixResponse(CreatePix::END_POINT, 'pix_create_request', $result);

    /** @var Basic $basic */
    /** @var Payer $payer */
    /** @var Item $items */
    [$basic, $payer, $items] = [...fakeBilletCreationBody()];

    $pix = PagHiper::pix(Cast::Response)->create($basic, $model, $items);

    expect($pix)
        ->toBeInstanceOf(Response::class)
        ->and($pix->json('pix_create_request.transaction_id'))
        ->toBe($transaction);
});
//endregion
