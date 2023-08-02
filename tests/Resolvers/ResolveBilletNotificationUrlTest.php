<?php

use DevAjMeireles\PagHiper\PagHiper;
use DevAjMeireles\PagHiper\Resolvers\Billet\ResolveBilletNotificationUrl;

it('should be able to resolve billet notification url successfully', function () {
    PagHiper::resolveBilletNotificationlUrlUsing(fn () => 'bar-foo');

    expect(app(ResolveBilletNotificationUrl::class)->resolve())->toBe('bar-foo');
});
