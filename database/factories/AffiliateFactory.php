<?php

namespace Database\Factories;

use App\Models\Affiliate;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Affiliate>
 */
class AffiliateFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $categories = \App\Models\ProductCategory::pluck('name')->toArray();
        if (empty($categories)) {
            $categories = ['Laravel', 'CodeIgniter', 'React', 'Vue', 'Template', 'Ebook'];
        }

        return [
            'name' => $this->faker->name(),
            'handle' => $this->faker->userName(),
            'avatar_url' => 'https://ui-avatars.com/api/?name=' . urlencode($this->faker->firstName()) . '&background=random&color=fff',
            'followers_count' => $this->faker->randomElement(['10,4RB', '982', '47', '123', '5,2RB', '1JT']),
            'clicks_count' => $this->faker->randomElement(['3RB', '8RB', '<1RB', '12RB']),
            'orders_count' => $this->faker->randomElement(['100-200', '200-500', '50-100', '500+']),
            'sales_range' => $this->faker->randomElement(['2JT - 10JT', '10JT - 50JT', '<2JT']),
            'audience_demographic' => 'Laki-laki, Umur 23-32',
            'platform' => $this->faker->randomElement(['instagram', 'youtube', 'tiktok', 'facebook', 'twitter']),
            'categories' => $this->faker->randomElements($categories, min(2, count($categories))),
            'is_golden_tick' => $this->faker->boolean(20),
            'is_good_sample_completion' => $this->faker->boolean(50),
        ];
    }
}
