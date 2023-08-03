<?php

namespace DevAjMeireles\PagHiper;

use DevAjMeireles\PagHiper\Actions\Pix\{CancelPix, CreatePix, NotificationPix, StatusPix};
use DevAjMeireles\PagHiper\DTO\Objects\Pix\Basic;
use DevAjMeireles\PagHiper\DTO\Objects\{Item, Payer, Pix\PagHiperPixNotification};
use DevAjMeireles\PagHiper\Enums\Cast;
use DevAjMeireles\PagHiper\Traits\ShareableBaseConstructor;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Client\Response;
use Illuminate\Http\Request as LaravelRequest;
use Illuminate\Support\Collection;

class Pix
{
    use ShareableBaseConstructor;

    public function create(Basic $basic, Payer|Model $payer, array|Item $items): Response|Collection|array|string
    {
        $response = CreatePix::execute($basic, $payer, $items);

        return $this->cast->response($response, 'pix_create_request');
    }

    public function status(string $transaction): Response|Collection|array|string
    {
        $response = StatusPix::execute($transaction);

        return $this->cast->response($response, 'status_request');
    }

    public function cancel(string $transaction): Response|Collection|array|string
    {
        $response = CancelPix::execute($transaction);

        return $this->cast->response($response, 'cancellation_request');
    }

    public function notification(string|LaravelRequest $notification, string $transaction = null): PagHiperPixNotification|Response|Collection|array|string
    {
        $response = NotificationPix::execute($notification, $transaction);

        if ($this->cast === Cast::BilletNotification) {
            return PagHiperPixNotification::make($response);
        }

        return $this->cast->response($response, 'status_request');
    }
}
