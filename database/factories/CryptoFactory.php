<?php

namespace Database\Factories;

use App\Models\Crypto;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\Factory;

class CryptoFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Crypto::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'symbol' => Str::random(rand(2, 8)),
            'name' => Str::random(rand(4, 12)),
            'cg_id' => Str::random(rand(5, 25)),
        ];
    }
}
