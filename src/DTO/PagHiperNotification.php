<?php

namespace DevAjMeireles\PagHiper\DTO;

use DevAjMeireles\PagHiper\Exceptions\NotificationModelNotFoundException;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\{Carbon, Collection};

class PagHiperNotification
{
    public function __construct(
        private readonly Collection $notification
    ) {
        //
    }

    public static function fromResponse(array $response): self
    {
        /** @phpstan-ignore-next-line */
        return new static(collect($response));
    }

    /** @throws NotificationModelNotFoundException */
    public function model(): Model
    {
        $order = $this->order();

        if (!str($order)->contains('|')) {
            throw new NotificationModelNotFoundException();
        }

        $order = str($order)->explode('|');

        $model = $order[1];
        $id    = $order[2];

        return (new $model())->findOrFail($id);
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

    public function payer(bool $toCollection = false): array|Collection
    {
        $payer = [
            'name'     => $this->notification->get('payer_name'),
            'email'    => $this->notification->get('payer_email'),
            'document' => $this->notification->get('payer_cpf_cnpj'),
            'phone'    => $this->notification->get('payer_phone'),
        ];

        if ($toCollection) {
            return collect($payer);
        }

        return $payer;
    }

    public function address(bool $toCollection = false): array|Collection
    {
        $payer = [
            'street'     => $this->notification->get('payer_street'),
            'number'     => $this->notification->get('payer_number'),
            'complement' => $this->notification->get('payer_complement'),
            'district'   => $this->notification->get('payer_district'),
            'city'       => $this->notification->get('payer_city'),
            'state'      => $this->notification->get('payer_state'),
            'zip_code'   => $this->notification->get('payer_zip_code'),
        ];

        if ($toCollection) {
            return collect($payer);
        }

        return $payer;
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

    public function items(bool $toCollection = false): array|Collection
    {
        $items = (array) $this->notification->get('items');

        if ($toCollection) {
            return collect($items);
        }

        return $items;
    }
}
