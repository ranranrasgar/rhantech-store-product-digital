<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Project;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ProjectImage>
 */
class ProjectImageFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $images = [
            'clients/5uKlsHixcbvMTNFU5uakoFPK50yJauF6LTwV6Huh.jpg',
            'clients/dtnKl61EmBQKX7zN7wlnAkcH8Hr6CdgCAKGmJV5P.jpg',
            'clients/e5BLDmbiPooA9qC96xRYKZraN8eaavOC3XaMqufe.jpg',
        ];

        return [
            'project_id' => Project::query()->inRandomOrder()->first()->id ?? null,
            'image' => fake()->randomElement($images),
            'caption' => fake()->sentence(),
            'sort_order' => fake()->numberBetween(0, 10),
        ];
    }
}
