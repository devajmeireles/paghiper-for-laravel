<?php

namespace DevAjMeireles\PagHiper\Database\Factories;

use DevAjMeireles\PagHiper\PagHiper;
use Illuminate\Database\Eloquent\Factories\Factory;

class PagHiperFactory extends Factory
{
    protected $model = PagHiper::class;

    public function definition(): array
    {
        return [
            'transaction' => $this->faker->numerify('##########'),
            'status' => $this->faker->randomElement([
                'pending',
                'reserved',
                'canceled',
                'completed',
                'paid',
                'processing',
                'refunded',
            ]),
            'url' => $this->faker->url(),
            'pdf' => $this->faker->url(),
            'digitable' => $this->faker->numerify('#####.##### #####.###### #####.###### # ##############'),
            'duedate_at' => $this->faker->dateTimeBetween('now', '+1 year'),
        ];
    }
}
