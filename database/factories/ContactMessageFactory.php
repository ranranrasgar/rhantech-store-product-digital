<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ContactMessage>
 */
class ContactMessageFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $fakerId = \Faker\Factory::create('id_ID');
        $isRead = $fakerId->boolean(60); // 60% read
        return [
            'name' => $fakerId->name(),
            'email' => $fakerId->safeEmail(),
            'phone' => $fakerId->phoneNumber(),
            'company' => $fakerId->company(),
            'subject' => $fakerId->sentence(),
            'message' => $fakerId->paragraph(),
            'status' => $isRead ? 'read' : 'unread',
            'read_at' => $isRead ? $fakerId->dateTimeBetween('-1 month', 'now') : null,
        ];
    }
}
