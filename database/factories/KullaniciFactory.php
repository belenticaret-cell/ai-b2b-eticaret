<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\Kullanici;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Kullanici>
 */
class KullaniciFactory extends Factory
{
    protected $model = Kullanici::class;

    public function definition(): array
    {
        return [
            'ad' => $this->faker->name(),
            'email' => $this->faker->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => Hash::make('password'),
            'rol' => 'musteri',
            'telefon' => $this->faker->phoneNumber(),
            'adres' => $this->faker->address(),
            'durum' => true,
            'remember_token' => Str::random(10),
        ];
    }

    public function admin(): static
    {
        return $this->state(fn () => [
            'rol' => 'admin',
        ]);
    }

    public function bayi(): static
    {
        return $this->state(fn () => [
            'rol' => 'bayi',
        ]);
    }
}
