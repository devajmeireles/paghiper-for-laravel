<?php

use DevAjMeireles\PagHiper\PagHiper;
use DevAjMeireles\PagHiper\Resolvers\Pix\ResolvePixNotificationUrl;

it('should be able to resolve pix notification url successfully', function () {
    PagHiper::resolvePixNotificationUlrUsing(fn () => 'bar-foo');

    expect(app(ResolvePixNotificationUrl::class)->resolve())->toBe('bar-foo');
});
