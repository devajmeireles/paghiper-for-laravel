<?php

use DevAjMeireles\PagHiper\Facades\PagHiper;
use DevAjMeireles\PagHiper\Resolvers\Billet\ResolveBilletNotificationUrl;

it('should be able to resolve billet notification url successfully', function () {
    PagHiper::resolveBilletNotificationUrlUsing(fn () => 'bar-foo');

    expect(app(ResolveBilletNotificationUrl::class)->resolve())->toBe('bar-foo');
});
