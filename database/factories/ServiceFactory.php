<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Service>
 */
class ServiceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $services = [
            [
                'name' => 'Custom Web Application Development',
                'description' => 'Kami membangun aplikasi web kustom yang scalable dan aman menggunakan teknologi terbaru seperti Laravel, React, dan Vue.js. Solusi kami disesuaikan dengan kebutuhan bisnis Anda, mulai dari sistem manajemen inventaris hingga portal e-commerce enterprise.',
                'icon' => 'code',
            ],
            [
                'name' => 'Mobile App Development',
                'description' => 'Pengembangan aplikasi mobile native dan cross-platform untuk iOS dan Android. Menggunakan Flutter dan React Native, kami menciptakan pengalaman pengguna yang mulus dan fitur yang responsif untuk menjangkau pengguna di perangkat mobile.',
                'icon' => 'smartphone',
            ],
            [
                'name' => 'IT Infrastructure & Networking',
                'description' => 'Desain, implementasi, dan pemeliharaan infrastruktur jaringan IT perusahaan. Layanan kami mencakup instalasi server fisik, konfigurasi router/switch MikroTik, manajemen firewall, dan optimalisasi bandwidth internet.',
                'icon' => 'dns',
            ],
            [
                'name' => 'Cloud Hosting & DevOps Services',
                'description' => 'Migrasi sistem on-premise ke cloud computing (AWS, Google Cloud, DigitalOcean) dan implementasi budaya DevOps. Kami membantu otomatisasi deployment (CI/CD), manajemen container Docker/Kubernetes, dan pemantauan sistem 24/7.',
                'icon' => 'cloud',
            ],
            [
                'name' => 'UI/UX Design & Prototyping',
                'description' => 'Desain antarmuka pengguna (UI) dan pengalaman pengguna (UX) yang berpusat pada end-user. Kami membuat wireframe, prototipe interaktif Figma, dan desain visual modern yang meningkatkan kenyamanan penggunaan dan konversi bisnis.',
                'icon' => 'design_services',
            ],
            [
                'name' => 'Cybersecurity & Penetration Testing',
                'description' => 'Melindungi aset digital dan data sensitif bisnis Anda dari ancaman siber. Kami melakukan audit keamanan komprehensif, penetration testing (ethical hacking), dan implementasi sistem pertahanan berlapis terhadap malware dan injeksi.',
                'icon' => 'security',
            ],
            [
                'name' => 'Data Science & Artificial Intelligence',
                'description' => 'Pemanfaatan big data untuk pengambilan keputusan bisnis cerdas. Kami mengembangkan model prediktif machine learning, analisis data, dan integrasi kecerdasan buatan seperti chatbot (NLP) untuk efisiensi operasional.',
                'icon' => 'smart_toy',
            ],
            [
                'name' => 'Search Engine Optimization (SEO)',
                'description' => 'Meningkatkan visibilitas website Anda di mesin pencari seperti Google. Kami melakukan optimasi on-page (kecepatan, struktur HTML) dan off-page, riset kata kunci, dan penyusunan strategi konten digital organik.',
                'icon' => 'search',
            ],
            [
                'name' => 'Internet of Things (IoT) Solutions',
                'description' => 'Menghubungkan perangkat fisik keras dengan internet untuk otomasi kontrol cerdas. Kami mengembangkan solusi IoT untuk otomatisasi industri manufaktur, smart home, dan sistem sensor monitoring jarak jauh berbasis mikrokontroler.',
                'icon' => 'router',
            ],
            [
                'name' => 'IT Consulting & System Integration',
                'description' => 'Konsultasi arsitektur perangkat lunak untuk transformasi digital perusahaan. Kami membantu memilih teknologi stack yang tepat dan mengintegrasikan berbagai API atau perangkat lunak legacy menjadi satu ekosistem sistem yang terpadu.',
                'icon' => 'support_agent',
            ],
        ];

        $service = $this->faker->unique()->randomElement($services);

        return [
            'name' => $service['name'],
            'slug' => Str::slug($service['name']),
            'description' => $service['description'],
            'icon' => $service['icon'],
            'is_active' => true,
            'sort_order' => $this->faker->numberBetween(0, 100),
        ];
    }
}
