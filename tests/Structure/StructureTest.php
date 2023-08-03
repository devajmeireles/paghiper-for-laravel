<?php

use DevAjMeireles\PagHiper\Billet;
use DevAjMeireles\PagHiper\DTO\Objects\Basic as BasicBillet;
use DevAjMeireles\PagHiper\DTO\Objects\Billet\Address;
use DevAjMeireles\PagHiper\DTO\Objects\Billet\PagHiperBilletNotification;
use DevAjMeireles\PagHiper\DTO\Objects\Item;
use DevAjMeireles\PagHiper\DTO\Objects\Payer;
use DevAjMeireles\PagHiper\DTO\Objects\Pix\Basic as BasicPix;
use DevAjMeireles\PagHiper\DTO\Objects\Pix\PagHiperPixNotification;
use DevAjMeireles\PagHiper\DTO\Objects\Traits\ShareableNotificationObject;
use DevAjMeireles\PagHiper\Enums\Cast;
use DevAjMeireles\PagHiper\Exceptions\NotificationModelNotFoundException;
use DevAjMeireles\PagHiper\Exceptions\PagHiperRejectException;
use DevAjMeireles\PagHiper\Exceptions\UnsupportedCastTypeExcetion;
use DevAjMeireles\PagHiper\Exceptions\WrongModelSetUpException;
use DevAjMeireles\PagHiper\PagHiper;
use DevAjMeireles\PagHiper\Pix;
use DevAjMeireles\PagHiper\Request;
use DevAjMeireles\PagHiper\Resolvers\Billet\ResolveBilletNotificationUrl;
use DevAjMeireles\PagHiper\Resolvers\Pix\ResolvePixNotificationUrl;
use DevAjMeireles\PagHiper\Resolvers\ResolverApi;
use DevAjMeireles\PagHiper\Resolvers\ResolveToken;
use DevAjMeireles\PagHiper\Traits\MakeableObject;
use DevAjMeireles\PagHiper\Traits\Resolveable;
use DevAjMeireles\PagHiper\Traits\ShareableBaseConstructor;

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
        NotificationModelNotFoundException::class,
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
        BasicPix::class,
        BasicBillet::class,
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
