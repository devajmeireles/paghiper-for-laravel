<?php

use DevAjMeireles\PagHiper\Facades\PagHiper;
use DevAjMeireles\PagHiper\Resolvers\ResolveToken;

it('should be able to resolve token successfully', function () {
    config([
        'paghiper' => ['token' => 'foo-bar'],
    ]);

    expect(config()->get('paghiper.token'))->toBe('foo-bar');

    PagHiper::resolveTokenUsing(fn () => 'bar-foo');

    expect(app(ResolveToken::class)->resolve())->toBe('bar-foo');
});
