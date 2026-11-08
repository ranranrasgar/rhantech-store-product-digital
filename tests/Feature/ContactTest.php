<?php

namespace Tests\Feature;

use App\Mail\ContactMessageNotification;
use App\Models\ContactMessage;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class ContactTest extends TestCase
{
    use RefreshDatabase;

    public function test_legitimate_contact_message_is_stored_and_emails_sent(): void
    {
        Mail::fake();

        $response = $this->post(route('contact.store'), [
            'name'           => 'Budi Santoso',
            'email'          => 'budi@example.com',
            'phone'          => '08123456789',
            'subject'        => 'Tanya Layanan Web',
            'message'        => 'Halo, saya ingin menanyakan estimasi biaya pembuatan aplikasi.',
            'form_loaded_at' => time() - 10,
            'website_url'    => '',
        ]);

        $response->assertSessionHas('success');
        $this->assertDatabaseHas('contact_messages', [
            'email'   => 'budi@example.com',
            'subject' => 'Tanya Layanan Web',
        ]);

        Mail::assertSent(ContactMessageNotification::class, 2);
    }

    public function test_honeypot_silently_drops_bot_submission(): void
    {
        Mail::fake();

        $response = $this->post(route('contact.store'), [
            'name'           => 'Spam Bot',
            'email'          => 'bot@spam.com',
            'subject'        => 'Free money',
            'message'        => 'Click here to claim your reward',
            'form_loaded_at' => time() - 10,
            'website_url'    => 'https://bot-trap.com',
        ]);

        $response->assertSessionHas('success');
        $this->assertDatabaseMissing('contact_messages', [
            'email' => 'bot@spam.com',
        ]);
        Mail::assertNothingSent();
    }

    public function test_cyrillic_russian_spam_is_silently_blocked(): void
    {
        Mail::fake();

        $response = $this->post(route('contact.store'), [
            'name'           => 'Ура! Ваш уникальный',
            'email'          => 'victim@gmail.com',
            'subject'        => 'вознаграждение в ожидании',
            'message'        => 'Проверьте детали по ссылке https://example.com',
            'form_loaded_at' => time() - 10,
            'website_url'    => '',
        ]);

        $response->assertSessionHas('success');
        $this->assertDatabaseMissing('contact_messages', [
            'email' => 'victim@gmail.com',
        ]);
        Mail::assertNothingSent();
    }

    public function test_phishing_shortener_url_is_silently_blocked(): void
    {
        Mail::fake();

        $response = $this->post(route('contact.store'), [
            'name'           => 'Spammer John',
            'email'          => 'target@gmail.com',
            'subject'        => 'Important Notice',
            'message'        => 'Claim your gift here: https://goo.su/XyZ123',
            'form_loaded_at' => time() - 10,
            'website_url'    => '',
        ]);

        $response->assertSessionHas('success');
        $this->assertDatabaseMissing('contact_messages', [
            'email' => 'target@gmail.com',
        ]);
        Mail::assertNothingSent();
    }

    public function test_instant_submission_without_timestamp_is_blocked(): void
    {
        Mail::fake();

        $response = $this->post(route('contact.store'), [
            'name'           => 'Fast Bot',
            'email'          => 'fast@spam.com',
            'subject'        => 'Fast submit',
            'message'        => 'Submitted within 0 seconds',
            'form_loaded_at' => '',
            'website_url'    => '',
        ]);

        $response->assertSessionHas('success');
        $this->assertDatabaseMissing('contact_messages', [
            'email' => 'fast@spam.com',
        ]);
        Mail::assertNothingSent();
    }
}
