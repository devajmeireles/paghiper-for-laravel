<?php

namespace DevAjMeireles\PagHiper\DTO\Objects;

use DevAjMeireles\PagHiper\Traits\MakeableObject;
use Illuminate\Contracts\Support\Arrayable;

class Basic implements Arrayable
{
    use MakeableObject;

    public function __construct(
        private readonly string|int $order_id,
        private readonly ?string $notification_url = null,
        private readonly int $days_due_date = 2,
        private readonly ?string $type_bank_slip = 'boletoA4',
        private readonly ?int $discount_cents = 0,
    ) {
        //
    }

    public function orderId(): string
    {
        return (string) $this->order_id;
    }

    public function daysDueDate(): int
    {
        return $this->days_due_date;
    }

    public function typeBankSlip(): string
    {
        return $this->type_bank_slip;
    }

    public function notificationUrl(): string
    {
        return $this->notification_url;
    }

    public function discountCents(): int
    {
        return (int) $this->discount_cents;
    }

    public function toArray(): array
    {
        return [
            'order_id'         => $this->orderId(),
            'days_due_date'    => $this->daysDueDate(),
            'type_bank_slip'   => $this->typeBankSlip(),
            'notification_url' => $this->notificationUrl(),
            'discount_cents'   => $this->discountCents(),
        ];
    }
}
