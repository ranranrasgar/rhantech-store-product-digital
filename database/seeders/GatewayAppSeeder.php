<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class GatewayAppSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\GatewayApp::firstOrCreate(
            ['name' => 'NOC Rhantech'],
            [
                'prefix' => '*',
                'callback_url' => 'https://noc.rhantech.com/api/webhooks/midtrans/callback',
                'is_active' => true,
            ]
        );
    }
}
