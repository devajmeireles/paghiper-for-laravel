<?php

use DevAjMeireles\PagHiper\Billet\Actions\Billet\CreateBillet;
use DevAjMeireles\PagHiper\Core\Contracts\PagHiperModelAbstraction;
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

    public function pagHiperDocument(): string
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

it('should be able to create billet for a model instance casting to array', function () use ($model) {
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

    [$payer, $basic, $address, $items] = [...fakeBilletCreationBody()];

    $billet = PagHiper::billet()->create($model, $basic, $address, $items);

    expect($billet)
        ->toBeArray()
        ->and($billet)
        ->toBe($result)
        ->and($billet['transaction_id'])
        ->toBe($transaction);
});

it('should be able to create billet for a model instance casting to collection', function () use ($model) {
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

    [$payer, $basic, $address, $items] = [...fakeBilletCreationBody()];

    $billet = PagHiper::billet('collection')->create($model, $basic, $address, $items);

    expect($billet)
        ->toBeInstanceOf(Collection::class)
        ->and($billet->get('transaction_id'))
        ->toBe($transaction)
        ->and($billet->toArray())
        ->toBe($result);
});

it('should be able to create billet for a model instance casting to original response', function () use ($model) {
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

    [$payer, $basic, $address, $items] = [...fakeBilletCreationBody()];

    $billet = PagHiper::billet('response')->create($model, $basic, $address, $items);

    expect($billet)
        ->toBeInstanceOf(Response::class)
        ->and($billet->json('create_request.transaction_id'))
        ->toBe($transaction)
        ->and($billet->json('create_request'))
        ->toBe($result);
});
