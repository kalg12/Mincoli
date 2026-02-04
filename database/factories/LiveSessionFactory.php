<?php

namespace Database\Factories;

use App\Models\LiveSession;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\LiveSession>
 */
class LiveSessionFactory extends Factory
{
    protected $model = LiveSession::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => $this->faker->sentence(),
            'platform' => $this->faker->randomElement(['Instagram Live', 'Facebook Live', 'TikTok Live', 'YouTube Live']),
            'live_url' => $this->faker->url(),
            'is_live' => false,
            'starts_at' => null,
            'ends_at' => null,
        ];
    }

    /**
     * State para una transmisión en vivo activa
     */
    public function active(): static
    {
        return $this->state(function (array $attributes) {
            return [
                'is_live' => true,
                'starts_at' => now()->subMinutes(rand(5, 60)),
                'ends_at' => null,
            ];
        });
    }

    /**
     * State para una transmisión que ha terminado
     */
    public function ended(): static
    {
        return $this->state(function (array $attributes) {
            return [
                'is_live' => false,
                'starts_at' => now()->subHours(2),
                'ends_at' => now()->subMinutes(30),
            ];
        });
    }

    /**
     * State para una transmisión programada
     */
    public function scheduled(): static
    {
        return $this->state(function (array $attributes) {
            return [
                'is_live' => false,
                'starts_at' => now()->addHours(2),
                'ends_at' => null,
            ];
        });
    }
}
