<?php

namespace DevAjMeireles\PagHiper\DTO\Objects;

use DevAjMeireles\PagHiper\Traits\MakeableObject;
use Illuminate\Contracts\Support\Arrayable;

class Basic implements Arrayable
{
    use MakeableObject;

    public function __construct(
        private readonly string|int $orderId,
        private readonly ?string $notificationUrl = null,
        private readonly int $daysDueDate = 2,
        private readonly ?string $typeBankSlip = 'boletoA4',
        private readonly ?int $discountCents = 0,
    ) {
        //
    }

    public function orderId(): string
    {
        return (string) $this->orderId;
    }

    public function daysDueDate(): int
    {
        return $this->daysDueDate;
    }

    public function typeBankSlip(): string
    {
        return $this->typeBankSlip;
    }

    public function notificationUrl(): string
    {
        return config('paghiper.notification_url') ?? $this->notificationUrl;
    }

    public function discountCents(): int
    {
        return (int) $this->discountCents;
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
