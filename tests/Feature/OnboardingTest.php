<?php

namespace Tests\Feature;

use App\Models\Store;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class OnboardingTest extends TestCase
{
    use RefreshDatabase;

    private function newUser(): User
    {
        return User::factory()->create(['onboarding_completed_at' => null]);
    }

    private function takenStore(string $slug = 'sudah-dipakai'): Store
    {
        return Store::forceCreate(['user_id' => User::factory()->create()->id, 'name' => 'Toko Lama', 'slug' => $slug]);
    }

    public function test_setup_form_keeps_typed_values_after_validation_error(): void
    {
        $this->takenStore();
        $user = $this->newUser();

        $this->actingAs($user)
            ->withSession(['onboarding_mode' => 'profile'])
            ->from(route('onboarding.setup'))
            ->post(route('onboarding.store'), ['name' => 'Ranran Studio', 'slug' => 'sudah-dipakai', 'description' => 'Kreator'])
            ->assertRedirect(route('onboarding.setup'))
            ->assertSessionHasErrors('slug');

        $this->actingAs($user)->get(route('onboarding.setup'))
            ->assertSee("name: 'Ranran Studio'", false)
            ->assertSee("slug: 'sudah-dipakai'", false);
    }

    public function test_uploaded_logo_survives_validation_error_and_is_used(): void
    {
        Storage::fake('public');
        $this->takenStore();
        $user = $this->newUser();

        $this->actingAs($user)
            ->withSession(['onboarding_mode' => 'profile'])
            ->from(route('onboarding.setup'))
            ->post(route('onboarding.store'), [
                'name' => 'Ranran Studio',
                'slug' => 'sudah-dipakai',
                'logo' => UploadedFile::fake()->image('logo.png'),
            ])
            ->assertSessionHasErrors('slug');

        $pendingLogo = session('onboarding_logo');
        $this->assertNotNull($pendingLogo);
        Storage::disk('public')->assertExists($pendingLogo);

        $this->actingAs($user)->get(route('onboarding.setup'))->assertSee(basename($pendingLogo), false);

        $this->actingAs($user)
            ->post(route('onboarding.store'), ['name' => 'Ranran Studio', 'slug' => 'ranran-studio'])
            ->assertRedirect(route('onboarding.complete'));

        $this->assertSame($pendingLogo, $user->fresh()->store->logo);
    }

    public function test_slug_generated_from_name_is_also_checked_for_uniqueness(): void
    {
        $this->takenStore('toko-lama');
        $user = $this->newUser();

        $this->actingAs($user)
            ->withSession(['onboarding_mode' => 'profile'])
            ->from(route('onboarding.setup'))
            ->post(route('onboarding.store'), ['name' => 'Toko Lama', 'slug' => ''])
            ->assertRedirect(route('onboarding.setup'))
            ->assertSessionHasErrors('slug');

        $this->assertNull($user->fresh()->store);
    }

    public function test_slug_check_reports_available_taken_and_reserved(): void
    {
        $this->takenStore();
        $user = $this->newUser();

        $this->actingAs($user)->getJson(route('onboarding.check_slug', ['slug' => 'nama-baru']))
            ->assertOk()->assertJson(['available' => true, 'slug' => 'nama-baru']);

        $this->actingAs($user)->getJson(route('onboarding.check_slug', ['slug' => 'sudah-dipakai']))
            ->assertOk()->assertJson(['available' => false, 'suggestion' => 'sudah-dipakai-2']);

        $this->actingAs($user)->getJson(route('onboarding.check_slug', ['slug' => 'about']))
            ->assertOk()->assertJson(['available' => false]);
    }

    public function test_bio_link_signup_lands_on_appearance_editor(): void
    {
        $user = $this->newUser();
        Store::forceCreate(['user_id' => $user->id, 'name' => 'Bio', 'slug' => 'bio-baru', 'store_mode' => 'profile']);

        $this->actingAs($user)->get(route('onboarding.complete'))
            ->assertRedirect(route('tenant.appearance.index', ['tab' => 'biolink', 'welcome' => 1]));
    }

    public function test_store_signup_lands_on_dashboard(): void
    {
        $user = $this->newUser();
        Store::forceCreate(['user_id' => $user->id, 'name' => 'Toko', 'slug' => 'toko-baru', 'store_mode' => 'store']);

        $this->actingAs($user)->get(route('onboarding.complete'))
            ->assertRedirect(route('tenant.dashboard'));
    }

    public function test_product_step_is_reachable_right_after_store_is_created(): void
    {
        $user = $this->newUser();

        $this->actingAs($user)
            ->withSession(['onboarding_mode' => 'store'])
            ->post(route('onboarding.store'), ['name' => 'Toko Baru', 'slug' => 'toko-baru-saya'])
            ->assertRedirect(route('onboarding.product'));

        $this->actingAs($user)->get(route('onboarding.product'))
            ->assertOk()
            ->assertSee('Upload produk pertamamu');
    }

    public function test_product_step_redirects_existing_stores_to_dashboard(): void
    {
        $user = $this->newUser();
        Store::forceCreate(['user_id' => $user->id, 'name' => 'Toko', 'slug' => 'toko-ada']);

        $this->actingAs($user)->get(route('onboarding.product'))
            ->assertRedirect(route('tenant.dashboard'));
    }

    public function test_onboarding_pages_have_no_emoji(): void
    {
        $user = $this->newUser();

        foreach (['onboarding.index', 'onboarding.setup'] as $route) {
            $html = $this->actingAs($user)->get(route($route))->getContent();
            $this->assertDoesNotMatchRegularExpression('/[\x{1F300}-\x{1FAFF}\x{26A1}]/u', $html, $route);
        }
    }

    public function test_appearance_editor_opens_for_new_bio_link_user(): void
    {
        $user = User::factory()->create(['onboarding_completed_at' => now(), 'email_verified_at' => now()]);
        Store::forceCreate(['user_id' => $user->id, 'name' => 'Bio', 'slug' => 'bio-baru', 'store_mode' => 'profile']);

        $this->actingAs($user)->get(route('tenant.appearance.index', ['tab' => 'biolink', 'welcome' => 1]))
            ->assertOk()
            ->assertSee('Profilmu sudah jadi')
            ->assertSee('phosphor-icons', false);
    }
}
