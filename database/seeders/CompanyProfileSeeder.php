<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\CompanyProfile;

class CompanyProfileSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        CompanyProfile::create([
            'company_name' => 'Rhansoft Management',
            'tagline' => 'Powering Your Business',
            'short_description' => 'We provide scalable solutions for modern businesses.',
            'description' => 'CorpEngine is a leading technology company specializing in building custom software, managing scalable infrastructures, and delivering innovative digital solutions.',
            'email' => 'contact@corpengine.com',
            'phone' => '+1234567890',
            'address' => '123 Tech Lane, Innovation City, 10110',
            'founded_year' => '2020',
        ]);
    }
}
