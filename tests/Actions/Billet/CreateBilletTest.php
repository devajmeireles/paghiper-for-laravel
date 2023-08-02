<?php

use DevAjMeireles\PagHiper\Actions\Billet\CreateBillet;
use DevAjMeireles\PagHiper\Contracts\PagHiperModelAbstraction;
use DevAjMeireles\PagHiper\Enums\Cast;
use DevAjMeireles\PagHiper\Exceptions\PagHiperRejectException;
use DevAjMeireles\PagHiper\PagHiper;
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
        return '89115748057';
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

it('should be able to create billet casting to array', function () {
    $result = [
        'result'           => 'success',
        'response_message' => 'transacao criada',
        'transaction_id'   => 'HF97T5SH2ZQNLF6Z',
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

    $billet = (new PagHiper())->billet()->create(...fakeBilletCreationBody());

    expect($billet)
        ->toBeArray()
        ->and($billet)
        ->toBe($result);
});

it('should be able to create billet casting to json', function () {
    $result = [
        'result'           => 'success',
        'response_message' => 'transacao criada',
        'transaction_id'   => $transaction = 'HF97T5SH2ZQNLF6Z',
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

    $billet = (new PagHiper())->billet(Cast::Json)->create(...fakeBilletCreationBody());

    expect($billet)
        ->toBeJson()
        ->and(json_decode($billet)->transaction_id)
        ->toBe($transaction);
});

it('should be able to create billet casting to collection', function (Cast $cast) {
    $result = [
        'result'           => 'success',
        'response_message' => 'transacao criada',
        'transaction_id'   => 'HF97T5SH2ZQNLF6Z',
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

    $billet = (new PagHiper())->billet(Cast::Collection)->create(...fakeBilletCreationBody());

    expect($billet)->toBeInstanceOf(Collection::class);
})->with([
    Cast::Collect,
    Cast::Collection,
]);

it('should be able to create billet casting to original response', function () {
    $result = [
        'result'           => 'success',
        'response_message' => 'transacao criada',
        'transaction_id'   => 'HF97T5SH2ZQNLF6Z',
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

    $billet = (new PagHiper())->billet(Cast::Response)->create(...fakeBilletCreationBody());

    expect($billet)->toBeInstanceOf(Response::class);
});

it('should be able to create billet using model', function () use ($model) {
    $result = [
        'result'           => 'success',
        'response_message' => 'transacao criada',
        'transaction_id'   => 'HF97T5SH2ZQNLF6Z',
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

    [$basic, $payer, $item] = [...fakeBilletCreationBody()];

    $billet = (new PagHiper())->billet()->create($basic, $model, $item);

    expect($billet)
        ->toBeArray()
        ->and($billet)
        ->toBe($result);
});

it('should be able to create billet casting to array with more than one item', function () {
    $result = [
        'result'           => 'success',
        'response_message' => 'transacao criada',
        'transaction_id'   => 'HF97T5SH2ZQNLF6Z',
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

    [$basic, $payer, $item] = [...fakeBilletCreationBody()];

    $clone  = clone $item;
    $billet = (new PagHiper())->billet()->create($basic, $payer, [$item, $clone]);

    expect($billet)
        ->toBeArray()
        ->toBe($result);
});

// TODO: talvez esse teste seja desnecessário
it('should be able to create billet with address as optional when model is used', function () use ($model) {
    $result = [
        'result'           => 'success',
        'response_message' => 'transacao criada',
        'transaction_id'   => 'HF97T5SH2ZQNLF6Z',
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

    [$basic, $payer, $item] = [...fakeBilletCreationBody()];

    $two    = clone $item;
    $billet = (new PagHiper())->billet()->create($basic, $model, [$item, $two]);

    expect($billet)
        ->toBeArray()
        ->toBe($result);
});

it('should be able to throw exception due response reject', function () {
    $this->expectException(PagHiperRejectException::class);
    $this->expectExceptionMessage("transaction_id não informada ou inválida");

    $result = [
        'result'           => 'reject',
        'response_message' => 'transaction_id não informada ou inválida',
    ];

    fakeBilletResponse(CreateBillet::END_POINT, 'create_request', $result);

    (new PagHiper())->billet()->create(...fakeBilletCreationBody());
});
