<?php

use DevAjMeireles\PagHiper\DTO\Objects\{Address, Basic, Item, Payer};
use DevAjMeireles\PagHiper\Enums\Cast;
use DevAjMeireles\PagHiper\Resolvers\Billet\{ResolveBilletNotificationUrl};
use DevAjMeireles\PagHiper\Resolvers\{ResolveToken, ResolverApi};
use DevAjMeireles\PagHiper\Traits\{MakeableObject, Resolveable};
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

test('makeable trait should only be used in objects')
    ->expect(MakeableObject::class)
    ->toOnlyBeUsedIn([
        Address::class,
        Item::class,
        Basic::class,
        Payer::class,
    ]);

test('resoveable trait should only be used in resolvers')
    ->expect(Resolveable::class)
    ->toOnlyBeUsedIn([
        ResolveToken::class,
        ResolverApi::class,
        ResolveBilletNotificationUrl::class,
    ]);
