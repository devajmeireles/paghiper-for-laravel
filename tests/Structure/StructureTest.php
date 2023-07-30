<?php

use DevAjMeireles\PagHiper\Enums\Cast;
use DevAjMeireles\PagHiper\{DTO\Objects\Address,
    DTO\Objects\Basic,
    DTO\Objects\Item,
    DTO\Objects\Payer,
    PagHiper,
    Request,
    Traits\MakeableObject};

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

test('makeable trait should only be used in objects')
    ->expect(MakeableObject::class)
    ->toOnlyBeUsedIn([
        Address::class,
        Item::class,
        Basic::class,
        Payer::class,
    ]);
