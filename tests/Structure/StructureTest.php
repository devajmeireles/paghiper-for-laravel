<?php

use DevAjMeireles\PagHiper\Enums\Cast;
use DevAjMeireles\PagHiper\{PagHiper, Request};

test('will not debugging functions')
    ->expect(['dd', 'dump', 'ray'])
    ->each->not->toBeUsed();

test('paghiper class should not extends nothing')
    ->expect(PagHiper::class)
    ->toExtendNothing();

test('request class should be final')
    ->expect(Request::class)
    ->toBeFinal();

test('exceptions should extends the default exception class')
    ->expect([
        DevAjMeireles\PagHiper\Exceptions\PagHiperRejectException::class,
        DevAjMeireles\PagHiper\Exceptions\UnsupportedCastTypeExcetion::class,
        DevAjMeireles\PagHiper\Exceptions\UnallowedCastTypeException::class,
        DevAjMeireles\PagHiper\Exceptions\WrongModelSetUpException::class,
    ])->toExtend(Exception::class);

test('enum should have all cases')
    ->expect(Cast::class)
    ->toBeEnum()
    ->toBeEnums();
