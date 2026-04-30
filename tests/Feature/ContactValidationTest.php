<?php

namespace Tests\Feature;

use App\Models\Contact;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class ContactValidationTest extends TestCase
{
    use DatabaseTransactions;

    public function test_create_contact_saves_contact_when_fields_are_valid(): void
    {
        $response = $this->actingAs(User::factory()->create())
            ->post(route('contacts.store'), [
                'name' => 'Valid Name',
                'contact' => '123456789',
                'email' => 'new@example.com',
            ]);

        $response->assertRedirect();

        $this->assertDatabaseHas('contacts', [
            'name' => 'Valid Name',
            'contact' => '123456789',
            'email' => 'new@example.com',
        ]);
    }

    public function test_create_contact_requires_a_name_with_at_least_six_characters(): void
    {
        $response = $this->actingAs(User::factory()->create())
            ->post(route('contacts.store'), [
                'name' => 'Short',
                'contact' => '123456789',
                'email' => 'new@example.com',
            ]);

        $response->assertSessionHasErrors('name');
    }

    public function test_create_contact_requires_contact_to_have_exactly_nine_digits(): void
    {
        $response = $this->actingAs(User::factory()->create())
            ->post(route('contacts.store'), [
                'name' => 'Valid Name',
                'contact' => '12345',
                'email' => 'new@example.com',
            ]);

        $response->assertSessionHasErrors('contact');
    }

    public function test_create_contact_requires_a_valid_email(): void
    {
        $response = $this->actingAs(User::factory()->create())
            ->post(route('contacts.store'), [
                'name' => 'Valid Name',
                'contact' => '123456789',
                'email' => 'invalid-email',
            ]);

        $response->assertSessionHasErrors('email');
    }

    public function test_create_contact_requires_unique_contact_and_unique_email(): void
    {
        Contact::factory()->create([
            'contact' => '123456789',
            'email' => 'existing@example.com',
        ]);

        $response = $this->actingAs(User::factory()->create())
            ->post(route('contacts.store'), [
                'name' => 'Valid Name',
                'contact' => '123456789',
                'email' => 'existing@example.com',
            ]);

        $response->assertSessionHasErrors(['contact', 'email']);
    }

    public function test_update_contact_requires_valid_fields(): void
    {
        $contact = Contact::factory()->create();

        $response = $this->actingAs(User::factory()->create())
            ->put(route('contacts.update', $contact), [
                'name' => 'Short',
                'contact' => '12345',
                'email' => 'invalid-email',
            ]);

        $response->assertSessionHasErrors(['name', 'contact', 'email']);
    }

    public function test_update_contact_requires_unique_contact_and_unique_email(): void
    {
        $contact = Contact::factory()->create();

        Contact::factory()->create([
            'contact' => '123456789',
            'email' => 'existing@example.com',
        ]);

        $response = $this->actingAs(User::factory()->create())
            ->put(route('contacts.update', $contact), [
                'name' => 'Valid Name',
                'contact' => '123456789',
                'email' => 'existing@example.com',
            ]);

        $response->assertSessionHasErrors(['contact', 'email']);
    }
}
