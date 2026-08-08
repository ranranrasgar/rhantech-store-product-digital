<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Client;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Testimonial>
 */
class TestimonialFactory extends Factory
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

        $faker = \Faker\Factory::create('id_ID');

        $indonesianTestimonials = [
            'Layanan yang diberikan sangat luar biasa. Tim sangat profesional dan responsif terhadap kebutuhan kami.',
            'Aplikasi yang dibuat sangat membantu operasional bisnis kami. Sangat direkomendasikan!',
            'Hasil kerja mereka melebihi ekspektasi. Desainnya modern dan sistemnya sangat stabil.',
            'Kerjasama yang hebat! Proses komunikasi berjalan lancar dari awal hingga proyek selesai.',
            'Sistem manajemen yang dibangun sangat efektif dan mudah digunakan oleh tim kami.',
            'Harga yang ditawarkan sangat sepadan dengan kualitas yang kami dapatkan. Terima kasih rhantech!',
            'Tim support sangat tanggap saat kami mengalami kendala teknis. Layanan after-sales yang memuaskan.',
            'Transformasi digital perusahaan kami berjalan sukses berkat bantuan dari tim profesional ini.'
        ];

        return [
            'client_id' => Client::query()->inRandomOrder()->first()->id ?? null,
            'name' => $faker->name(),
            'position' => $faker->jobTitle(),
            'company' => $faker->company(),
            'photo' => $faker->randomElement($images),
            'content' => $faker->randomElement($indonesianTestimonials),
            'rating' => $faker->numberBetween(4, 5),
            'is_active' => $faker->boolean(90),
            'sort_order' => $faker->numberBetween(0, 100),
        ];
    }
}
