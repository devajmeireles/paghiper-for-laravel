<?php

use DevAjMeireles\PagHiper\Actions\Pix\NotificationPix;
use DevAjMeireles\PagHiper\DTO\Objects\Pix\PagHiperPixNotification;
use DevAjMeireles\PagHiper\DTO\Objects\{Item, Payer};
use DevAjMeireles\PagHiper\Enums\Cast;
use DevAjMeireles\PagHiper\PagHiper;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Carbon;

it('should be able to consult notification casting to array', function () {
    $transaction = '3IMZI5QXGMI7K40W';

    $result = [
        "result"               => "success",
        "response_message"     => "notification_id encontrada",
        "transaction_id"       => $transaction,
        "order_id"             => "96874",
        "created_date"         => "2017-07-14 21:21:02",
        "status"               => "paid",
        "paid_date"            => "2017-07-20 05:21:02",
        "status_date"          => "2017-07-20 05:24:02",
        "payer_email"          => "poulsilva@myexemple.com",
        "payer_name"           => "poul silva",
        "payer_cpf_cnpj"       => "00000000191",
        "payer_phone"          => "1140638785",
        "payer_street"         => "Av Brigadeiro Faria Lima",
        "payer_number"         => "1461",
        "payer_complement"     => "Torre Sul 4º Andar",
        "payer_district"       => "Jardim Paulistano",
        "payer_city"           => "São Paulo",
        "payer_state"          => "SP",
        "payer_zip_code"       => "01452002",
        "value_cents"          => "17012",
        "value_fee_cents"      => "279",
        "discount_cents"       => "1100",
        "shipping_price_cents" => "2595",
        "pix_code"             => [
            "qrcode_base64" => "iVBORw0KGgoAAAANSUhEUgAAAPoAAAD6",
            "emv"           => "00020101021226770014BR.GOV.BCB.PIX2555api.itau/pix/qr/v1/ce7f9743-6575-485a-86da-a0f56cf136565204000053039865802BR5925PAGHIPER SERV ONLINE EIRE6009SAO PAULO62070503***630409AD",
            "pix_url"       => "https://pix.paghiper.com/qrcode/180068c7/HF97T5SH2ZQNLF6Z/30039",
            "bacen_url"     => "https://pix.bcb.gov.br/qr/MDAwMjAxMDEwMjEyMjY3NzAwMTRCUi5HT1YuQkNCLlBJWDI1NTVhcGkuaXRhdS9waXgvcXIvdjEvY2U3Zjk3NDMtNjU3NS00ODVhLTg2ZGEtYTBmNTZjZjEzNjU2NTIwNDAwMDA1MzAzOTg2NTgwMkJSNTkyNVBBR0hJUEVSIFNFUlYgT05MSU5FIEVJUkU2MDA5U0FPIFBBVUxPNjIwNzA1MDMqKio2MzA0MDlBRA==",
        ],
        "due_date"       => "2017-07-31",
        "num_cart_items" => "3",
        "items"          => [
            [
                "item_id"     => "1",
                "description" => "piscina de bolinha",
                "quantity"    => "1",
                "price_cents" => "1012",
            ],
        ],
        "http_code" => "201",
    ];

    fakePixResponse(NotificationPix::END_POINT, 'status_request', $result);

    $notification = (new PagHiper())->pix()->notification("W6QM6MORZW4KUENC0NU6ERN0AULFUIUROKEU72L6ZQQT4E6521CGT0G3V2JQKDI9", $transaction);

    expect($notification)
        ->toBeArray()
        ->and($notification['transaction_id'])
        ->toBe($transaction);
});

it('should be able to consult notification casting to json', function () {
    $transaction = '3IMZI5QXGMI7K40W';

    $result = [
        "result"               => "success",
        "response_message"     => "notification_id encontrada",
        "transaction_id"       => $transaction,
        "order_id"             => "96874",
        "created_date"         => "2017-07-14 21:21:02",
        "status"               => "paid",
        "paid_date"            => "2017-07-20 05:21:02",
        "status_date"          => "2017-07-20 05:24:02",
        "payer_email"          => "poulsilva@myexemple.com",
        "payer_name"           => "poul silva",
        "payer_cpf_cnpj"       => "00000000191",
        "payer_phone"          => "1140638785",
        "payer_street"         => "Av Brigadeiro Faria Lima",
        "payer_number"         => "1461",
        "payer_complement"     => "Torre Sul 4º Andar",
        "payer_district"       => "Jardim Paulistano",
        "payer_city"           => "São Paulo",
        "payer_state"          => "SP",
        "payer_zip_code"       => "01452002",
        "value_cents"          => "17012",
        "value_fee_cents"      => "279",
        "discount_cents"       => "1100",
        "shipping_price_cents" => "2595",
        "pix_code"             => [
            "qrcode_base64" => "iVBORw0KGgoAAAANSUhEUgAAAPoAAAD6",
            "emv"           => "00020101021226770014BR.GOV.BCB.PIX2555api.itau/pix/qr/v1/ce7f9743-6575-485a-86da-a0f56cf136565204000053039865802BR5925PAGHIPER SERV ONLINE EIRE6009SAO PAULO62070503***630409AD",
            "pix_url"       => "https://pix.paghiper.com/qrcode/180068c7/HF97T5SH2ZQNLF6Z/30039",
            "bacen_url"     => "https://pix.bcb.gov.br/qr/MDAwMjAxMDEwMjEyMjY3NzAwMTRCUi5HT1YuQkNCLlBJWDI1NTVhcGkuaXRhdS9waXgvcXIvdjEvY2U3Zjk3NDMtNjU3NS00ODVhLTg2ZGEtYTBmNTZjZjEzNjU2NTIwNDAwMDA1MzAzOTg2NTgwMkJSNTkyNVBBR0hJUEVSIFNFUlYgT05MSU5FIEVJUkU2MDA5U0FPIFBBVUxPNjIwNzA1MDMqKio2MzA0MDlBRA==",
        ],
        "due_date"       => "2017-07-31",
        "num_cart_items" => "3",
        "items"          => [
            [
                "item_id"     => "1",
                "description" => "piscina de bolinha",
                "quantity"    => "1",
                "price_cents" => "1012",
            ],
        ],
        "http_code" => "201",
    ];

    fakePixResponse(NotificationPix::END_POINT, 'status_request', $result);

    $notification = (new PagHiper())->pix(Cast::Json)->notification("W6QM6MORZW4KUENC0NU6ERN0AULFUIUROKEU72L6ZQQT4E6521CGT0G3V2JQKDI9", $transaction);

    expect($notification)
        ->toBeJson()
        ->and(json_decode($notification)->transaction_id)
        ->toBe($transaction);
});

it('should be able to consult notification casting to collection', function () {
    $transaction = '3IMZI5QXGMI7K40W';

    $result = [
        "result"               => "success",
        "response_message"     => "notification_id encontrada",
        "transaction_id"       => $transaction,
        "order_id"             => "96874",
        "created_date"         => "2017-07-14 21:21:02",
        "status"               => "paid",
        "paid_date"            => "2017-07-20 05:21:02",
        "status_date"          => "2017-07-20 05:24:02",
        "payer_email"          => "poulsilva@myexemple.com",
        "payer_name"           => "poul silva",
        "payer_cpf_cnpj"       => "00000000191",
        "payer_phone"          => "1140638785",
        "payer_street"         => "Av Brigadeiro Faria Lima",
        "payer_number"         => "1461",
        "payer_complement"     => "Torre Sul 4º Andar",
        "payer_district"       => "Jardim Paulistano",
        "payer_city"           => "São Paulo",
        "payer_state"          => "SP",
        "payer_zip_code"       => "01452002",
        "value_cents"          => "17012",
        "value_fee_cents"      => "279",
        "discount_cents"       => "1100",
        "shipping_price_cents" => "2595",
        "pix_code"             => [
            "qrcode_base64" => "iVBORw0KGgoAAAANSUhEUgAAAPoAAAD6",
            "emv"           => "00020101021226770014BR.GOV.BCB.PIX2555api.itau/pix/qr/v1/ce7f9743-6575-485a-86da-a0f56cf136565204000053039865802BR5925PAGHIPER SERV ONLINE EIRE6009SAO PAULO62070503***630409AD",
            "pix_url"       => "https://pix.paghiper.com/qrcode/180068c7/HF97T5SH2ZQNLF6Z/30039",
            "bacen_url"     => "https://pix.bcb.gov.br/qr/MDAwMjAxMDEwMjEyMjY3NzAwMTRCUi5HT1YuQkNCLlBJWDI1NTVhcGkuaXRhdS9waXgvcXIvdjEvY2U3Zjk3NDMtNjU3NS00ODVhLTg2ZGEtYTBmNTZjZjEzNjU2NTIwNDAwMDA1MzAzOTg2NTgwMkJSNTkyNVBBR0hJUEVSIFNFUlYgT05MSU5FIEVJUkU2MDA5U0FPIFBBVUxPNjIwNzA1MDMqKio2MzA0MDlBRA==",
        ],
        "due_date"       => "2017-07-31",
        "num_cart_items" => "3",
        "items"          => [
            [
                "item_id"     => "1",
                "description" => "piscina de bolinha",
                "quantity"    => "1",
                "price_cents" => "1012",
            ],
        ],
        "http_code" => "201",
    ];

    fakePixResponse(NotificationPix::END_POINT, 'status_request', $result);

    $notification = (new PagHiper())->pix(Cast::Collection)->notification("W6QM6MORZW4KUENC0NU6ERN0AULFUIUROKEU72L6ZQQT4E6521CGT0G3V2JQKDI9", $transaction);

    expect($notification)
        ->toBeCollection()
        ->and($notification->get('transaction_id'))
        ->toBe($transaction);
});

it('should be able to consult notification casting to original response', function () {
    $transaction = '3IMZI5QXGMI7K40W';

    $result = [
        "result"               => "success",
        "response_message"     => "notification_id encontrada",
        "transaction_id"       => $transaction,
        "order_id"             => "96874",
        "created_date"         => "2017-07-14 21:21:02",
        "status"               => "paid",
        "paid_date"            => "2017-07-20 05:21:02",
        "status_date"          => "2017-07-20 05:24:02",
        "payer_email"          => "poulsilva@myexemple.com",
        "payer_name"           => "poul silva",
        "payer_cpf_cnpj"       => "00000000191",
        "payer_phone"          => "1140638785",
        "payer_street"         => "Av Brigadeiro Faria Lima",
        "payer_number"         => "1461",
        "payer_complement"     => "Torre Sul 4º Andar",
        "payer_district"       => "Jardim Paulistano",
        "payer_city"           => "São Paulo",
        "payer_state"          => "SP",
        "payer_zip_code"       => "01452002",
        "value_cents"          => "17012",
        "value_fee_cents"      => "279",
        "discount_cents"       => "1100",
        "shipping_price_cents" => "2595",
        "pix_code"             => [
            "qrcode_base64" => "iVBORw0KGgoAAAANSUhEUgAAAPoAAAD6",
            "emv"           => "00020101021226770014BR.GOV.BCB.PIX2555api.itau/pix/qr/v1/ce7f9743-6575-485a-86da-a0f56cf136565204000053039865802BR5925PAGHIPER SERV ONLINE EIRE6009SAO PAULO62070503***630409AD",
            "pix_url"       => "https://pix.paghiper.com/qrcode/180068c7/HF97T5SH2ZQNLF6Z/30039",
            "bacen_url"     => "https://pix.bcb.gov.br/qr/MDAwMjAxMDEwMjEyMjY3NzAwMTRCUi5HT1YuQkNCLlBJWDI1NTVhcGkuaXRhdS9waXgvcXIvdjEvY2U3Zjk3NDMtNjU3NS00ODVhLTg2ZGEtYTBmNTZjZjEzNjU2NTIwNDAwMDA1MzAzOTg2NTgwMkJSNTkyNVBBR0hJUEVSIFNFUlYgT05MSU5FIEVJUkU2MDA5U0FPIFBBVUxPNjIwNzA1MDMqKio2MzA0MDlBRA==",
        ],
        "due_date"       => "2017-07-31",
        "num_cart_items" => "3",
        "items"          => [
            [
                "item_id"     => "1",
                "description" => "piscina de bolinha",
                "quantity"    => "1",
                "price_cents" => "1012",
            ],
        ],
        "http_code" => "201",
    ];

    fakePixResponse(NotificationPix::END_POINT, 'status_request', $result);

    $notification = (new PagHiper())->pix(Cast::Response)
        ->notification("W6QM6MORZW4KUENC0NU6ERN0AULFUIUROKEU72L6ZQQT4E6521CGT0G3V2JQKDI9", $transaction);

    expect($notification)
        ->toBeInstanceOf(Response::class)
        ->and($notification->json('status_request.transaction_id'))
        ->toBe($transaction);
});

it('should be able to consult notification casting to notification dto', function () {
    $transaction = '3IMZI5QXGMI7K40W';

    $result = [
        "result"               => "success",
        "response_message"     => "notification_id encontrada",
        "transaction_id"       => $transaction,
        "order_id"             => "96874",
        "created_date"         => "2017-07-14 21:21:02",
        "status"               => "paid",
        "paid_date"            => "2017-07-20 05:21:02",
        "status_date"          => "2017-07-20 05:24:02",
        "payer_email"          => "poulsilva@myexemple.com",
        "payer_name"           => "poul silva",
        "payer_cpf_cnpj"       => "00000000191",
        "payer_phone"          => "1140638785",
        "payer_street"         => "Av Brigadeiro Faria Lima",
        "payer_number"         => "1461",
        "payer_complement"     => "Torre Sul 4º Andar",
        "payer_district"       => "Jardim Paulistano",
        "payer_city"           => "São Paulo",
        "payer_state"          => "SP",
        "payer_zip_code"       => "01452002",
        "value_cents"          => "17012",
        "value_fee_cents"      => "279",
        "discount_cents"       => "1100",
        "shipping_price_cents" => "2595",
        "pix_code"             => [
            "qrcode_base64" => "iVBORw0KGgoAAAANSUhEUgAAAPoAAAD6",
            "emv"           => "00020101021226770014BR.GOV.BCB.PIX2555api.itau/pix/qr/v1/ce7f9743-6575-485a-86da-a0f56cf136565204000053039865802BR5925PAGHIPER SERV ONLINE EIRE6009SAO PAULO62070503***630409AD",
            "pix_url"       => "https://pix.paghiper.com/qrcode/180068c7/HF97T5SH2ZQNLF6Z/30039",
            "bacen_url"     => "https://pix.bcb.gov.br/qr/MDAwMjAxMDEwMjEyMjY3NzAwMTRCUi5HT1YuQkNCLlBJWDI1NTVhcGkuaXRhdS9waXgvcXIvdjEvY2U3Zjk3NDMtNjU3NS00ODVhLTg2ZGEtYTBmNTZjZjEzNjU2NTIwNDAwMDA1MzAzOTg2NTgwMkJSNTkyNVBBR0hJUEVSIFNFUlYgT05MSU5FIEVJUkU2MDA5U0FPIFBBVUxPNjIwNzA1MDMqKio2MzA0MDlBRA==",
        ],
        "due_date"       => "2017-07-31",
        "num_cart_items" => "1",
        "items"          => [
            [
                "item_id"     => "1",
                "description" => "piscina de bolinha",
                "quantity"    => "1",
                "price_cents" => "1012",
            ],
        ],
        "http_code" => "201",
    ];

    fakePixResponse(NotificationPix::END_POINT, 'status_request', $result);

    $notification = (new PagHiper())->pix(Cast::PixNotification)
        ->notification("W6QM6MORZW4KUENC0NU6ERN0AULFUIUROKEU72L6ZQQT4E6521CGT0G3V2JQKDI9", $transaction);

    expect($notification)
        ->toBeInstanceOf(PagHiperPixNotification::class)
        ->and($notification->transactionId())
        ->toBe($transaction)
        ->and($notification->paid())
        ->toBeTrue()
        ->and($createdAt = $notification->createDate())
        ->toBeInstanceOf(Carbon::class)
        ->and($createdAt->format('Y-m-d H:i:s'))
        ->toBe('2017-07-14 21:21:02')
        ->and($notification->paidDate())
        ->toBeInstanceOf(Carbon::class)
        ->and($notification->paidDate()->format('Y-m-d H:i:s'))
        ->toBe('2017-07-20 05:21:02')
        ->and($notification->numCartItems())
        ->toBe(1)
        ->and($notification->items())
        ->toBeInstanceOf(Item::class)
        ->and($notification->items())
        ->and($slip = $notification->bankSlip())
        ->toBeArray()
        ->and($slip)
        ->toBeEmpty()
        ->and($notification->payer())
        ->toBeInstanceOf(Payer::class)
        ->and($dueDate = $notification->dueDate())
        ->toBeInstanceOf(Carbon::class)
        ->and($dueDate->format('Y-m-d'))
        ->toBe('2017-07-31')
        ->and($notification->valueCents())
        ->toBe(17012)
        ->and($notification->discountCents())
        ->toBe(1100)
        ->and($notification->original())
        ->toBeInstanceOf(Response::class)
        ->and($notification->status())
        ->toBe("paid");
});
