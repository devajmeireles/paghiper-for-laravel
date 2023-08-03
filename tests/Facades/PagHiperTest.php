<?php

use DevAjMeireles\PagHiper\Actions\Billet\StatusBillet;
use DevAjMeireles\PagHiper\Facades\PagHiper;
use DevAjMeireles\PagHiper\Resolvers\Billet\ResolveBilletNotificationUrl;
use DevAjMeireles\PagHiper\Resolvers\Pix\ResolvePixNotificationUrl;
use DevAjMeireles\PagHiper\Resolvers\ResolverApi;
use DevAjMeireles\PagHiper\Resolvers\ResolveToken;

it('facade was bounded', function () {
    expect($this->app->bound(PagHiper::class))->toBeTrue();
});

it('facade should be mocked', function () {
    $result = [
        'result'           => 'reject',
        'response_message' => 'token ou apiKey inválidos',
    ];

    // this set the test to throw exception...
    fakeBilletResponse(StatusBillet::END_POINT, 'status_request', $result);

    $transaction = 'BPV661O7AVLORCN5';

    // this set the test to return an array (indicating success)...
    PagHiper::shouldReceive('billet->status')
        ->with($transaction)
        ->andReturn([
            'status_result' => $result = [
                'result'           => 'success',
                'response_message' => 'transacao encontrada',
                'status'           => 'pending',
                'status_date'      => '2017-07-14 21:21:02',
                'due_date'         => '2017-07-12',
                'value_cents'      => '2000',
                'bank_slip'        => [
                    'digitable_line' => '34191.76106 04487.160246 61514.190000 3 72180000002000',
                    'url_slip'       => 'https://www.paghiper.com/checkout/boleto/ XXXXXXXXXXXXXXX',
                    'url_slip_pdf'   => 'https://www.paghiper.com/checkout/boleto/XXXXXXXXXXXXXXX/pdf',
                ],
                'http_code' => '201',
            ],
        ]);

    expect(PagHiper::billet()->status($transaction))->toBe(['status_result' => $result]);
});

test('smart resolver credentials should work successfully', function () {
    PagHiper::resolveCredentialsUsing(
        api: fn () => 'foo-api',
        token: fn () => 'foo-token',
    );

    expect(app(ResolverApi::class)->resolve())
        ->toBe('foo-api')
        ->and(app(ResolveToken::class)->resolve())
        ->toBe('foo-token');
});

test('smart resolver urls should work successfully', function () {
    PagHiper::resolveNotificationUrlUsing(
        billet: fn () => 'foo-billet-url',
        pix: fn () => 'foo-pix-url',
    );

    expect(app(ResolveBilletNotificationUrl::class)->resolve())
        ->toBe('foo-billet-url')
        ->and(app(ResolvePixNotificationUrl::class)->resolve())
        ->toBe('foo-pix-url');
});
