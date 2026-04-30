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
                'contact' => '870000001',
                'email' => 'contact870000001@example.com',
            ]);

        $response->assertRedirect();

        $this->assertDatabaseHas('contacts', [
            'name' => 'Valid Name',
            'contact' => '870000001',
            'email' => 'contact870000001@example.com',
        ]);
    }

    public function test_create_contact_requires_a_name_with_at_least_six_characters(): void
    {
        $response = $this->actingAs(User::factory()->create())
            ->post(route('contacts.store'), [
                'name' => 'Short',
                'contact' => '870000002',
                'email' => 'contact870000002@example.com',
            ]);

        $response->assertSessionHasErrors('name');
    }

    public function test_create_contact_requires_contact_to_have_exactly_nine_digits(): void
    {
        $response = $this->actingAs(User::factory()->create())
            ->post(route('contacts.store'), [
                'name' => 'Valid Name',
                'contact' => '00011144',
                'email' => 'contact870000003@example.com',
            ]);

        $response->assertSessionHasErrors('contact');
    }

    public function test_create_contact_rejects_contact_with_more_than_nine_digits(): void
    {
        $response = $this->actingAs(User::factory()->create())
            ->post(route('contacts.store'), [
                'name' => 'Valid Name',
                'contact' => '0001114444',
                'email' => 'contact870000004@example.com',
            ]);

        $response->assertSessionHasErrors('contact');
    }

    public function test_create_contact_requires_a_valid_email(): void
    {
        $response = $this->actingAs(User::factory()->create())
            ->post(route('contacts.store'), [
                'name' => 'Valid Name',
                'contact' => '870000005',
                'email' => 'invalid-email',
            ]);

        $response->assertSessionHasErrors('email');
    }

    public function test_create_contact_requires_unique_contact_and_unique_email(): void
    {
        Contact::factory()->create([
            'contact' => '870000006',
            'email' => 'contact870000006@example.com',
        ]);

        $response = $this->actingAs(User::factory()->create())
            ->post(route('contacts.store'), [
                'name' => 'Valid Name',
                'contact' => '870000006',
                'email' => 'contact870000006@example.com',
            ]);

        $response->assertSessionHasErrors(['contact', 'email']);
    }

    public function test_update_contact_saves_contact_when_fields_are_valid(): void
    {
        $contact = Contact::factory()->create([
            'name' => 'Old Name',
            'contact' => '870000007',
            'email' => 'contact870000007@example.com',
        ]);

        $response = $this->actingAs(User::factory()->create())
            ->put(route('contacts.update', $contact), [
                'name' => 'Updated Name',
                'contact' => '870000008',
                'email' => 'contact870000008@example.com',
            ]);

        $response->assertRedirect();

        $this->assertDatabaseHas('contacts', [
            'id' => $contact->id,
            'name' => 'Updated Name',
            'contact' => '870000008',
            'email' => 'contact870000008@example.com',
        ]);
    }

    public function test_update_contact_requires_valid_fields(): void
    {
        $contact = Contact::factory()->create([
            'contact' => '870000009',
            'email' => 'contact870000009@example.com',
        ]);

        $response = $this->actingAs(User::factory()->create())
            ->put(route('contacts.update', $contact), [
                'name' => 'Short',
                'contact' => '000111',
                'email' => 'invalid-email',
            ]);

        $response->assertSessionHasErrors(['name', 'contact', 'email']);
    }

    public function test_update_contact_requires_unique_contact_and_unique_email(): void
    {
        $contact = Contact::factory()->create([
            'contact' => '870000010',
            'email' => 'contact870000010@example.com',
        ]);

        Contact::factory()->create([
            'contact' => '870000011',
            'email' => 'contact870000011@example.com',
        ]);

        $response = $this->actingAs(User::factory()->create())
            ->put(route('contacts.update', $contact), [
                'name' => 'Valid Name',
                'contact' => '870000011',
                'email' => 'contact870000011@example.com',
            ]);

        $response->assertSessionHasErrors(['contact', 'email']);
    }

    public function test_delete_contact_soft_deletes_contact(): void
    {
        $contact = Contact::factory()->create([
            'contact' => '870000012',
            'email' => 'contact870000012@example.com',
        ]);

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
            'contact' => '870000013',
            'email' => 'contact870000013@example.com',
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
        $contact = Contact::factory()->create([
            'contact' => '870000014',
            'email' => 'contact870000014@example.com',
        ]);

        $response = $this->get(route('contacts.show', $contact));

        $response->assertRedirect(route('login'));
    }

    public function test_guest_cannot_access_contact_edit_page(): void
    {
        $contact = Contact::factory()->create([
            'contact' => '870000015',
            'email' => 'contact870000015@example.com',
        ]);

        $response = $this->get(route('contacts.edit', $contact));

        $response->assertRedirect(route('login'));
    }

    public function test_guest_cannot_delete_contact(): void
    {
        $contact = Contact::factory()->create([
            'contact' => '870000016',
            'email' => 'contact870000016@example.com',
        ]);

        $response = $this->delete(route('contacts.destroy', $contact));

        $response->assertRedirect(route('login'));

        $this->assertDatabaseHas('contacts', [
            'id' => $contact->id,
            'deleted_at' => null,
        ]);
    }

    public function test_authenticated_user_can_access_protected_contact_pages(): void
    {
        $contact = Contact::factory()->create([
            'contact' => '870000017',
            'email' => 'contact870000017@example.com',
        ]);
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
