<?php

namespace DevAjMeireles\PagHiper\DTO\Objects;

use DevAjMeireles\PagHiper\Traits\MakeableObject;
use Illuminate\Contracts\Support\Arrayable;

class Basic implements Arrayable
{
    use MakeableObject;

    public function __construct(
        private ?string $order_id = null,
        private ?string $notification_url = null,
        private ?int $days_due_date = 2,
        private ?int $minutes_due_date = null,
        private ?string $type_bank_slip = 'boletoA4',
        private ?int $discount_cents = 0,
        private ?int $shipping_price_cents = null,
        private ?string $shipping_methods = null,
        private ?string $partners_id = null,
        private ?int $number_ntfiscal = null,
        private ?bool $fixed_description = null,
        private ?string $seller_description = null,
        private ?int $late_payment_fine = null,
        private ?bool $per_day_interest = null,
        private ?int $early_payment_discounts_days = null,
        private ?int $early_payment_discounts_cents = null,
        private ?int $open_after_day_due = null,
    ) {
        //
    }

    public function toArray(): array
    {
        return [
            'order_id'                      => $this->order_id,
            'notification_url'              => $this->notification_url,
            'days_due_date'                 => $this->days_due_date,
            'minutes_due_date'              => $this->minutes_due_date,
            'type_bank_slip'                => $this->type_bank_slip,
            'discount_cents'                => $this->discount_cents,
            'shipping_price_cents'          => $this->shipping_price_cents,
            'shipping_methods'              => $this->shipping_methods,
            'partners_id'                   => $this->partners_id,
            'number_ntfiscal'               => $this->number_ntfiscal,
            'fixed_description'             => $this->fixed_description,
            'seller_description'            => $this->seller_description,
            'late_payment_fine'             => $this->late_payment_fine,
            'per_day_interest'              => $this->per_day_interest,
            'early_payment_discounts_days'  => $this->early_payment_discounts_days,
            'early_payment_discounts_cents' => $this->early_payment_discounts_cents,
            'open_after_day_due'            => $this->open_after_day_due,
        ];
    }
}
