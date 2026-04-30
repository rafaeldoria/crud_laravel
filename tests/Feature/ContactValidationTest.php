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

    public function test_create_contact_rejects_contact_with_more_than_nine_digits(): void
    {
        $response = $this->actingAs(User::factory()->create())
            ->post(route('contacts.store'), [
                'name' => 'Valid Name',
                'contact' => '1234567890',
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

    public function test_update_contact_saves_contact_when_fields_are_valid(): void
    {
        $contact = Contact::factory()->create([
            'name' => 'Old Name',
            'contact' => '123456789',
            'email' => 'old@example.com',
        ]);

        $response = $this->actingAs(User::factory()->create())
            ->put(route('contacts.update', $contact), [
                'name' => 'Updated Name',
                'contact' => '987654321',
                'email' => 'updated@example.com',
            ]);

        $response->assertRedirect();

        $this->assertDatabaseHas('contacts', [
            'id' => $contact->id,
            'name' => 'Updated Name',
            'contact' => '987654321',
            'email' => 'updated@example.com',
        ]);
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

    public function test_delete_contact_soft_deletes_contact(): void
    {
        $contact = Contact::factory()->create();

        $response = $this->actingAs(User::factory()->create())
            ->delete(route('contacts.destroy', $contact));

        $response->assertRedirect(route('contacts.index'));

        $this->assertSoftDeleted('contacts', [
            'id' => $contact->id,
        ]);
    }

    public function test_guest_can_view_contact_listing(): void
    {
        $contact = Contact::factory()->create([
            'name' => 'Visible Contact',
        ]);

        $response = $this->get(route('contacts.index'));

        $response->assertOk();
        $response->assertSee($contact->name);
    }

    public function test_guest_cannot_access_contact_create_page(): void
    {
        $response = $this->get(route('contacts.create'));

        $response->assertRedirect(route('login'));
    }

    public function test_guest_cannot_access_contact_details_page(): void
    {
        $contact = Contact::factory()->create();

        $response = $this->get(route('contacts.show', $contact));

        $response->assertRedirect(route('login'));
    }

    public function test_guest_cannot_access_contact_edit_page(): void
    {
        $contact = Contact::factory()->create();

        $response = $this->get(route('contacts.edit', $contact));

        $response->assertRedirect(route('login'));
    }

    public function test_guest_cannot_delete_contact(): void
    {
        $contact = Contact::factory()->create();

        $response = $this->delete(route('contacts.destroy', $contact));

        $response->assertRedirect(route('login'));

        $this->assertDatabaseHas('contacts', [
            'id' => $contact->id,
            'deleted_at' => null,
        ]);
    }

    public function test_authenticated_user_can_access_protected_contact_pages(): void
    {
        $contact = Contact::factory()->create();
        $user = User::factory()->create();

        $this->actingAs($user)
            ->get(route('contacts.create'))
            ->assertOk();

        $this->actingAs($user)
            ->get(route('contacts.show', $contact))
            ->assertOk();

        $this->actingAs($user)
            ->get(route('contacts.edit', $contact))
            ->assertOk();
    }
}
