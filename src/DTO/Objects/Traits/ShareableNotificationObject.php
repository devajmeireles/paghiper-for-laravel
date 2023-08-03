<?php

namespace DevAjMeireles\PagHiper\DTO\Objects\Traits;

use DevAjMeireles\PagHiper\DTO\Objects\Billet\Address;
use DevAjMeireles\PagHiper\DTO\Objects\{Billet\PagHiperBilletNotification, Item, Payer};
use DevAjMeireles\PagHiper\Exceptions\NotificationModelNotFoundException;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Client\Response;
use Illuminate\Support\{Carbon, Collection};

trait ShareableNotificationObject
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

    public function original(): Response
    {
        return $this->response;
    }

    public function type(): string
    {
        return $this instanceof PagHiperBilletNotification ? 'billet' : 'pix';
    }

    public function transactionId(): string
    {
        return $this->notification->get('transaction_id');
    }

    public function orderId(): string
    {
        return $this->notification->get('order_id');
    }

    public function createDate(): Carbon
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

    public function paidDate(): Carbon
    {
        return Carbon::parse($this->notification->get('paid_date'));
    }

    public function valueCents(): int
    {
        return $this->notification->get('value_cents');
    }

    public function valueFeeCents(): int
    {
        return $this->notification->get('value_fee_cents');
    }

    public function valueCentsPaid(): int
    {
        return $this->notification->get('value_cents_paid');
    }

    public function latePaymentFine(): int
    {
        return $this->notification->get('late_payment_fine');
    }

    public function perDayInterest(): bool
    {
        return $this->notification->get('per_day_interest');
    }

    public function earlyPaymentDiscountsDays(): int
    {
        return $this->notification->get('early_payment_discounts_days');
    }

    public function earlyPaymentDiscountsCents(): int
    {
        return $this->notification->get('early_payment_discounts_cents');
    }

    public function openAfterDayDue(): int
    {
        return $this->notification->get('open_after_day_due');
    }

    public function shippingPriceCents(): int
    {
        return $this->notification->get('shipping_price_cents');
    }

    public function discountCents(): int
    {
        return $this->notification->get('discount_cents');
    }

    public function numCartItems(): int
    {
        return $this->notification->get('num_cart_items');
    }

    public function dueDate(): Carbon
    {
        return Carbon::parse($this->notification->get('due_date'));
    }

    public function dueDateTime(): Carbon
    {
        return Carbon::parse($this->notification->get('due_date_time'));
    }

    public function bankSlip(): array
    {
        return [...$this->notification->get('bank_slip') ?? []];
    }

    public function pixCode(): array|false
    {
        return [...$this->notification->get('pix_code') ?? []];
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

    public function payer(): Payer
    {
        $payer = Payer::make()
            ->set('name', $this->notification->get('payer_name'))
            ->set('email', $this->notification->get('payer_email'))
            ->set('cpf_cnpj', $this->notification->get('payer_cpf_cnpj'))
            ->set('phone', $this->notification->get('payer_phone'));

        if ($this instanceof PagHiperBilletNotification) {
            $payer->set(
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

        return $payer;
    }

    public function modelable(bool $exception = true): Model|null
    {
        $order      = $this->orderId();
        $stringable = str($order);

        if (!$stringable->contains('|') && !$stringable->contains(':')) {
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

        return $exception
            ? (new $model())->findOrFail($id)
            : (new $model())->find($id);
    }
}
