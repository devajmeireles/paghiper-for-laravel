<?php

use DevAjMeireles\PagHiper\Facades\PagHiper;
use DevAjMeireles\PagHiper\Resolvers\ResolverApi;

it('should be able to resolve api successfully', function () {
    config([
        'paghiper' => ['api' => 'foo-bar'],
    ]);

    expect(config()->get('paghiper.api'))->toBe('foo-bar');

    PagHiper::resolveApiUsing(fn () => 'bar-foo');

    expect(app(ResolverApi::class)->resolve())->toBe('bar-foo');
});
