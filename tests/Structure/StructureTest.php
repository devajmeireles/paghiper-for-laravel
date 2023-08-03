<?php

use DevAjMeireles\PagHiper\DTO\Objects\{Billet\Address,
    Billet\Basic as BasicBillet,
    Billet\PagHiperBilletNotification,
    Item,
    Payer,
    Pix\Basic as BasicPix,
    Pix\PagHiperPixNotification,
    Traits\ShareableNotificationObject
};
use DevAjMeireles\PagHiper\Enums\Cast;
use DevAjMeireles\PagHiper\Resolvers\Billet\{ResolveBilletNotificationUrl};
use DevAjMeireles\PagHiper\Resolvers\{Pix\ResolvePixNotificationUrl, ResolveToken, ResolverApi};
use DevAjMeireles\PagHiper\Traits\{MakeableObject, Resolveable, ShareableBaseConstructor};
use DevAjMeireles\PagHiper\{Billet,
    Exceptions\PagHiperRejectException,
    Exceptions\UnsupportedCastTypeExcetion,
    Exceptions\WrongModelSetUpException,
    PagHiper,
    Pix,
    Request};

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
        PagHiperRejectException::class,
        UnsupportedCastTypeExcetion::class,
        WrongModelSetUpException::class,
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
        BasicBillet::class,
        BasicPix::class,
        Payer::class,
    ]);

test('resoveable trait should only be used in resolvers')
    ->expect(Resolveable::class)
    ->toOnlyBeUsedIn([
        ResolveToken::class,
        ResolverApi::class,
        ResolveBilletNotificationUrl::class,
        ResolvePixNotificationUrl::class,
    ]);

test('shareable object should only be used in dtos')
    ->expect(ShareableNotificationObject::class)
    ->toOnlyBeUsedIn([
        PagHiperBilletNotification::class,
        PagHiperPixNotification::class,
    ]);

test('shareable base structure should only be used in base class')
    ->expect(ShareableBaseConstructor::class)
    ->toOnlyBeUsedIn([
        Pix::class,
        Billet::class,
    ]);
