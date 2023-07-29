<?php

namespace DevAjMeireles\PagHiper\Core\DTO\Objects;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Support\Carbon;

class Basic implements Arrayable
{
    public function __construct(
        private readonly string $id,
        private readonly string $notification,
        private readonly int|Carbon $dueDate = 2,
        private readonly ?string $type = 'boletoA4',
        private readonly ?int $discount = 0,
    ) {
        //
    }

    public function id(): string
    {
        return $this->id;
    }

    public function dueDate(): int|Carbon
    {
        return $this->dueDate;
    }

    public function type(): string
    {
        return $this->type;
    }

    public function notification(): string
    {
        return $this->notification;
    }

    public function discount(): int
    {
        return $this->discount;
    }

    public function toArray(): array
    {
        return [
            'order_id'         => $this->id(),
            'days_due_date'    => $this->dueDate(),
            'type_bank_slip'   => $this->type(),
            'notification_url' => $this->notification(),
            'discount_cents'   => $this->discount(),
        ];
    }
}
