<?php

namespace DevAjMeireles\PagHiper\Database\Factories;

use DevAjMeireles\PagHiper\Billet;
use Illuminate\Database\Eloquent\Factories\Factory;

class BilletFactory extends Factory
{
    protected $model = Billet::class;

    public function definition(): array
    {
        return [
            'billable_id' => $this->faker->randomNumber(),
            'billable_type' => $this->faker->randomElement([
                'App\Models\User',
                'App\Models\Company',
            ]),
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
