<?php

namespace DevAjMeireles\PagHiper\DTO;

use DevAjMeireles\PagHiper\DTO\Objects\{Billet\Address, Billet\Item, Billet\Payer};
use DevAjMeireles\PagHiper\Exceptions\NotificationModelNotFoundException;
use Illuminate\Database\Eloquent\{Model, ModelNotFoundException};
use Illuminate\Http\Client\Response;
use Illuminate\Support\{Carbon, Collection};

class PagHiperNotification
{
    public function __construct(
        private readonly Response $response,
        private readonly Collection $notification
    ) {
        //
    }

    public static function make(Response $response): self
    {
        /** @phpstan-ignore-next-line */
        return new static($response, collect($response->json('status_request')));
    }

    public function transaction(): string
    {
        return $this->notification->get('transaction_id');
    }

    public function order(): string
    {
        return $this->notification->get('order_id');
    }

    public function createdAt(): Carbon
    {
        return Carbon::parse($this->notification->get('created_date'));
    }

    public function status(): string
    {
        return $this->notification->get('status');
    }

    public function pending(): bool
    {
        return $this->notification->get('status') === 'pending';
    }

    public function reserved(): bool
    {
        return $this->notification->get('status') === 'reserved';
    }

    public function canceled(): bool
    {
        return $this->notification->get('status') === 'canceled';
    }

    public function completed(): bool
    {
        return $this->notification->get('status') === 'completed';
    }

    public function paid(): bool
    {
        return $this->notification->get('status') === 'paid';
    }

    public function processing(): bool
    {
        return $this->notification->get('status') === 'processing';
    }

    public function refunded(): bool
    {
        return $this->notification->get('status') === 'refunded';
    }

    public function paidAt(): Carbon
    {
        return Carbon::parse($this->notification->get('paid_date'));
    }

    public function payer(): Payer
    {
        return Payer::make()
            ->set('name', $this->notification->get('payer_name'))
            ->set('email', $this->notification->get('payer_email'))
            ->set('cpf_cnpj', $this->notification->get('payer_cpf_cnpj'))
            ->set('phone', $this->notification->get('payer_phone'))
            ->set(
                'address',
                Address::make()
                    ->set('street', $this->notification->get('payer_street'))
                    ->set('number', $this->notification->get('payer_number'))
                    ->set('complement', $this->notification->get('payer_complement'))
                    ->set('district', $this->notification->get('payer_district'))
                    ->set('city', $this->notification->get('payer_city'))
                    ->set('state', $this->notification->get('payer_state'))
                    ->set('zip_code', $this->notification->get('payer_zip_code'))
            );
    }

    public function finalPrice(): string
    {
        return $this->notification->get('value_cents');
    }

    public function discount(): string
    {
        return $this->notification->get('discount_cents');
    }

    public function bankSlip(): array
    {
        return [...$this->notification->get('bank_slip')];
    }

    public function dueDateAt(): Carbon
    {
        return Carbon::parse($this->notification->get('due_date'));
    }

    public function numItems(): int
    {
        return (int) $this->notification->get('num_cart_items');
    }

    public function items(): array|Item
    {
        $response = [];
        $items    = $this->notification->get('items');

        foreach ($items as $item) {
            $response[] = Item::make([...$item]);
        }

        if (count($response) === 1) {
            return $response[0];
        }

        return $response;
    }

    public function original(): Response
    {
        return $this->response;
    }

    /** @throws NotificationModelNotFoundException|ModelNotFoundException */
    public function modelable(bool $exception = true): Model|null
    {
        $order = $this->order();

        if (!str($order)->contains('|') && !str($order)->contains(':')) {
            if ($exception) {
                throw new NotificationModelNotFoundException();
            }

            return null;
        }

        $unparsed = explode('|', $order);
        $parsed   = explode(':', $unparsed[1]);

        $model = $parsed[0];
        $id    = $parsed[1];

        if (!class_exists($model)) {
            if ($exception) {
                throw new NotificationModelNotFoundException();
            }

            return null;
        }

        if ($exception) {
            return (new $model())->findOrFail($id);
        }

        return (new $model())->find($id);
    }
}
