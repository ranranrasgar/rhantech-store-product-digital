<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Client>
 */
class ClientFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $fakerId = \Faker\Factory::create('id_ID');
        $images = [
            'clients/5uKlsHixcbvMTNFU5uakoFPK50yJauF6LTwV6Huh.jpg',
            'clients/dtnKl61EmBQKX7zN7wlnAkcH8Hr6CdgCAKGmJV5P.jpg',
            'clients/e5BLDmbiPooA9qC96xRYKZraN8eaavOC3XaMqufe.jpg',
        ];

        return [
            'name' => $fakerId->company(),
            'address' => $fakerId->address(),
            'phone' => $fakerId->phoneNumber(),
            'email' => $fakerId->unique()->safeEmail(),
            'website' => $fakerId->url(),
            'logo' => $fakerId->randomElement($images),
            'description' => $fakerId->paragraph(),
            'is_active' => $fakerId->boolean(80), // 80% active
        ];
    }
}
