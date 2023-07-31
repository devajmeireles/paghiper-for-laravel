<?php

namespace DevAjMeireles\PagHiper\DTO\Objects\Billet;

use DevAjMeireles\PagHiper\Traits\MakeableObject;
use Illuminate\Contracts\Support\Arrayable;

class Basic implements Arrayable
{
    use MakeableObject;

    public function __construct(
        private ?string $order_id = null,
        private ?string $notification_url = null,
        private ?int $days_due_date = 2,
        private ?string $type_bank_slip = 'boletoA4',
        private ?int $discount_cents = 0,
    ) {
        //
    }

    public function toArray(): array
    {
        return [
            'order_id'         => $this->order_id,
            'notification_url' => $this->notification_url,
            'days_due_date'    => $this->days_due_date,
            'type_bank_slip'   => $this->type_bank_slip,
            'discount_cents'   => $this->discount_cents,
        ];
    }
}
