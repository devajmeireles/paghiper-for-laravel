<?php

namespace DevAjMeireles\PagHiper\Core\DTO\Objects;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Support\Carbon;

class Basic implements Arrayable
{
    public function __construct(
        private readonly string $orderId,
        private readonly string $notificationUrl,
        private readonly int|Carbon $daysDueDate = 2,
        private readonly ?string $typeBankSlip = 'boletoA4',
        private readonly ?int $discountCents = 0,
    ) {
        //
    }

    public function orderId(): string
    {
        return $this->orderId;
    }

    public function daysDueDate(): int|Carbon
    {
        return $this->daysDueDate;
    }

    public function typeBankSlip(): string
    {
        return $this->typeBankSlip;
    }

    public function notificationUrl(): string
    {
        return $this->notificationUrl;
    }

    public function discountCents(): int
    {
        return $this->discountCents;
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
