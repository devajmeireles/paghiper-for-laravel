<?php

use DevAjMeireles\PagHiper\Actions\Billet\NotificationBillet;
use DevAjMeireles\PagHiper\DTO\Objects\Billet\PagHiperBilletNotification;
use DevAjMeireles\PagHiper\DTO\Objects\Item;
use DevAjMeireles\PagHiper\DTO\Objects\{Payer};
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
        "bank_slip"            => [
            "digitable_line" => "34191.76437 47416.610245 61514.190000 9 72540000017012",
            "url_slip"       => "https://www.paghiper.com/checkout/boleto/ab039901bd6f402e44424f30cd1d3ca9e1f5c90bdb78f7878e8dcdcf7701e1befdc7f1ff6521e8312f7cef408b10b500ee85da4b8903d28874a0436f00a0a3c6/3IMZI5QXGMI7K40W/43474166",
            "url_slip_pdf"   => "https://www.paghiper.com/checkout/boleto/ab039901bd6f402e44424f30cd1d3ca9e1f5c90bdb78f7878e8dcdcf7701e1befdc7f1ff6521e8312f7cef408b10b500ee85da4b8903d28874a0436f00a0a3c6/3IMZI5QXGMI7K40W/43474166/pdf",
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

    fakeBilletResponse(NotificationBillet::END_POINT, 'status_request', $result);

    $notification = (new PagHiper())->billet()->notification("W6QM6MORZW4KUENC0NU6ERN0AULFUIUROKEU72L6ZQQT4E6521CGT0G3V2JQKDI9", $transaction);

    expect($notification)
        ->toBeArray()
        ->and($notification['transaction_id'])
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
        "bank_slip"            => [
            "digitable_line" => "34191.76437 47416.610245 61514.190000 9 72540000017012",
            "url_slip"       => "https://www.paghiper.com/checkout/boleto/ab039901bd6f402e44424f30cd1d3ca9e1f5c90bdb78f7878e8dcdcf7701e1befdc7f1ff6521e8312f7cef408b10b500ee85da4b8903d28874a0436f00a0a3c6/3IMZI5QXGMI7K40W/43474166",
            "url_slip_pdf"   => "https://www.paghiper.com/checkout/boleto/ab039901bd6f402e44424f30cd1d3ca9e1f5c90bdb78f7878e8dcdcf7701e1befdc7f1ff6521e8312f7cef408b10b500ee85da4b8903d28874a0436f00a0a3c6/3IMZI5QXGMI7K40W/43474166/pdf",
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

    fakeBilletResponse(NotificationBillet::END_POINT, 'status_request', $result);

    $notification = (new PagHiper())->billet(Cast::Collection)
        ->notification("W6QM6MORZW4KUENC0NU6ERN0AULFUIUROKEU72L6ZQQT4E6521CGT0G3V2JQKDI9", $transaction);

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
        "bank_slip"            => [
            "digitable_line" => "34191.76437 47416.610245 61514.190000 9 72540000017012",
            "url_slip"       => "https://www.paghiper.com/checkout/boleto/ab039901bd6f402e44424f30cd1d3ca9e1f5c90bdb78f7878e8dcdcf7701e1befdc7f1ff6521e8312f7cef408b10b500ee85da4b8903d28874a0436f00a0a3c6/3IMZI5QXGMI7K40W/43474166",
            "url_slip_pdf"   => "https://www.paghiper.com/checkout/boleto/ab039901bd6f402e44424f30cd1d3ca9e1f5c90bdb78f7878e8dcdcf7701e1befdc7f1ff6521e8312f7cef408b10b500ee85da4b8903d28874a0436f00a0a3c6/3IMZI5QXGMI7K40W/43474166/pdf",
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

    fakeBilletResponse(NotificationBillet::END_POINT, 'status_request', $result);

    $notification = (new PagHiper())->billet(Cast::Response)
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
        "bank_slip"            => [
            "digitable_line" => "34191.76437 47416.610245 61514.190000 9 72540000017012",
            "url_slip"       => "https://www.paghiper.com/checkout/boleto/ab039901bd6f402e44424f30cd1d3ca9e1f5c90bdb78f7878e8dcdcf7701e1befdc7f1ff6521e8312f7cef408b10b500ee85da4b8903d28874a0436f00a0a3c6/3IMZI5QXGMI7K40W/43474166",
            "url_slip_pdf"   => "https://www.paghiper.com/checkout/boleto/ab039901bd6f402e44424f30cd1d3ca9e1f5c90bdb78f7878e8dcdcf7701e1befdc7f1ff6521e8312f7cef408b10b500ee85da4b8903d28874a0436f00a0a3c6/3IMZI5QXGMI7K40W/43474166/pdf",
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

    fakeBilletResponse(NotificationBillet::END_POINT, 'status_request', $result);

    $notification = (new PagHiper())->billet(Cast::BilletNotification)
        ->notification("W6QM6MORZW4KUENC0NU6ERN0AULFUIUROKEU72L6ZQQT4E6521CGT0G3V2JQKDI9", $transaction);

    expect($notification)
        ->toBeInstanceOf(PagHiperBilletNotification::class)
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
        ->toBe([
            "digitable_line" => "34191.76437 47416.610245 61514.190000 9 72540000017012",
            "url_slip"       => "https://www.paghiper.com/checkout/boleto/ab039901bd6f402e44424f30cd1d3ca9e1f5c90bdb78f7878e8dcdcf7701e1befdc7f1ff6521e8312f7cef408b10b500ee85da4b8903d28874a0436f00a0a3c6/3IMZI5QXGMI7K40W/43474166",
            "url_slip_pdf"   => "https://www.paghiper.com/checkout/boleto/ab039901bd6f402e44424f30cd1d3ca9e1f5c90bdb78f7878e8dcdcf7701e1befdc7f1ff6521e8312f7cef408b10b500ee85da4b8903d28874a0436f00a0a3c6/3IMZI5QXGMI7K40W/43474166/pdf",
        ])
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
