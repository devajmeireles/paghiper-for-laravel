<?php

use DevAjMeireles\PagHiper\Billet\Billet;
use DevAjMeireles\PagHiper\Core\Request\Request;
use DevAjMeireles\PagHiper\Core\Traits\InteractWithCasts;
use DevAjMeireles\PagHiper\PagHiper;

test('will not debugging functions')
    ->expect(['dd', 'dump', 'ray'])
    ->each->not->toBeUsed();

test('paghiper class should not extends nothing')
    ->expect(PagHiper::class)
    ->toExtendNothing();

test('request class should be final')
    ->expect(Request::class)
    ->toBeFinal();

test('InteractWithCasts trait should be used only in Billet')
    ->expect(InteractWithCasts::class)
    ->toOnlyBeUsedIn(Billet::class);