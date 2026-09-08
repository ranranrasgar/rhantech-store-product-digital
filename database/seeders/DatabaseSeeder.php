<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::updateOrCreate(
            ['email' => 'admin@rhantech.com'],
            [
                'name' => 'Admin',
                'password' => bcrypt('Admin123456!'),
                'email_verified_at' => now(),
                'role' => 'Admin',
            ]
        );

        $this->call([
            CompanyProfileSeeder::class,
            MasterDataSeeder::class,
            ProductCategoryAndTypeSeeder::class,
            ProductSeeder::class,
            GatewayAppSeeder::class,
            HelpCenterSeeder::class,
        ]);

        if (app()->environment('local') || class_exists(\Faker\Factory::class)) {
            // Seed exact amounts requested
            $this->command->info('Seeding Clients...');
            \App\Models\Client::factory(500)->create();

            $this->command->info('Seeding Services...');
            \App\Models\Service::factory(10)->create();

            $this->command->info('Seeding Projects...');
            // We chunk it into batches to avoid memory bloat
            for ($i = 0; $i < 10; $i++) {
                \App\Models\Project::factory(10)->create(); // 10 x 10 = 100 projects
            }

            $this->command->info('Seeding Project Images...');
            for ($i = 0; $i < 10; $i++) {
                \App\Models\ProjectImage::factory(10)->create();
            }

            $this->command->info('Seeding Testimonials...');
            \App\Models\Testimonial::factory(50)->create();

            $this->command->info('Seeding Contact Messages...');
            \App\Models\ContactMessage::factory(100)->create();
        } else {
            $this->command->info('Skipping dummy factory data in production (Faker is not installed).');
        }



        $this->command->info('Seeding completed successfully!');
    }
}
