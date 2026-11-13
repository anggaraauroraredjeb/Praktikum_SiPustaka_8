<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Inertia\Testing\AssertableInertia as Assert;
use Modules\Pendaftaran\Models\Book;
use Modules\Pendaftaran\Models\Loan;
use Tests\TestCase;

class SiPustakaTest extends TestCase
{
    use RefreshDatabase;

    public function test_auth_and_module_pages_render_for_the_expected_roles(): void
    {
        config(['inertia.testing.ensure_pages_exist' => false]);

        $this->get(route('home'))->assertInertia(fn (Assert $page) => $page->component('Home'));
        $this->get(route('login'))->assertInertia(fn (Assert $page) => $page->component('Auth/Login'));
        $this->get(route('register'))->assertInertia(fn (Assert $page) => $page->component('Auth/Register'));

        $user = User::factory()->create(['role' => 'user']);
        $this->actingAs($user)->get(route('dashboard'))->assertInertia(fn (Assert $page) => $page->component('Dashboard'));
        $this->actingAs($user)->get(route('pendaftaran.index'))->assertInertia(fn (Assert $page) => $page->component('Pendaftaran/Index'));

        $admin = User::factory()->create(['role' => 'admin']);
        $this->actingAs($admin)->get(route('dashboard'))->assertInertia(fn (Assert $page) => $page->component('Dashboard'));
        $this->actingAs($admin)->get(route('pendaftaran.index'))->assertInertia(fn (Assert $page) => $page->component('Pendaftaran/Index'));
        $this->actingAs($admin)->get(route('users.index'))->assertInertia(fn (Assert $page) => $page->component('UserManagement/Index'));
    }

    public function test_registration_always_creates_a_regular_user(): void
    {
        $response = $this->post(route('register.store'), [
            'name' => 'Ayu Pustaka',
            'email' => 'ayu@example.test',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'role' => 'admin',
        ]);

        $response->assertRedirect(route('dashboard'));
        $this->assertDatabaseHas('users', ['email' => 'ayu@example.test', 'role' => 'user']);
    }

    public function test_regular_users_cannot_open_user_management_or_change_books(): void
    {
        $user = User::factory()->create(['role' => 'user']);
        $this->actingAs($user)->get(route('users.index'))->assertForbidden();
        $this->actingAs($user)->postJson(route('pendaftaran.books.store'), [
            'title' => 'Buku Rahasia', 'author' => 'Penulis', 'stock' => 1,
        ])->assertForbidden();
    }

    public function test_ajax_validation_returns_field_errors_from_form_requests(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $response = $this->actingAs($admin)->postJson(route('pendaftaran.books.store'), [
            'title' => '', 'author' => '', 'stock' => -1,
        ])->assertUnprocessable()->assertJsonValidationErrors(['title', 'author', 'stock']);
        $response->assertJsonPath('errors.title.0', 'Judul buku wajib diisi.');
    }

    public function test_admin_can_create_users_but_cannot_demote_their_own_account(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $this->actingAs($admin)->post(route('users.store'), [
            'name' => 'Operator Baru',
            'email' => 'operator@example.test',
            'role' => 'user',
            'password' => 'password123',
        ])->assertRedirect();
        $this->assertDatabaseHas('users', ['email' => 'operator@example.test', 'role' => 'user']);

        $this->put(route('users.update', $admin), [
            'name' => $admin->name,
            'email' => $admin->email,
            'role' => 'user',
        ])->assertRedirect();
        $this->assertSame('admin', $admin->fresh()->role);
    }

    public function test_user_can_request_a_loan_with_a_supported_document(): void
    {
        Storage::fake('public');
        $user = User::factory()->create(['role' => 'user']);
        $book = Book::create(['title' => 'Sebuah Buku', 'author' => 'Penulis', 'stock' => 2]);

        $response = $this->actingAs($user)->postJson(route('pendaftaran.loans.store'), [
            'book_id' => $book->id,
            'borrower_name' => $user->name,
            'school' => 'SMK Negeri 1',
            'study_program' => 'Rekayasa Perangkat Lunak',
            'email' => $user->email,
            'phone' => '08123456789',
            'loan_date' => '2026-11-06',
            'return_date' => '2026-11-13',
            'document' => UploadedFile::fake()->create('surat.pdf', 100, 'application/pdf'),
        ]);

        $response->assertCreated()->assertJsonPath('loan.status', 'Dipinjam');
        $this->assertDatabaseHas('loans', ['user_id' => $user->id, 'book_id' => $book->id]);
        $this->assertSame(1, $book->fresh()->stock);
        $this->assertTrue(Storage::disk('public')->exists(Loan::first()->document_path));
    }

    public function test_admin_cannot_reopen_a_loan_when_the_book_is_out_of_stock(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $borrower = User::factory()->create(['role' => 'user']);
        $book = Book::create(['title' => 'Buku Habis', 'author' => 'Penulis', 'stock' => 0]);
        $loan = Loan::create([
            'user_id' => $borrower->id,
            'book_id' => $book->id,
            'borrower_name' => $borrower->name,
            'school' => 'SMK Negeri 1',
            'study_program' => 'RPL',
            'email' => $borrower->email,
            'phone' => '08123456789',
            'loan_date' => '2026-11-01',
            'return_date' => '2026-11-08',
            'status' => 'Dikembalikan',
        ]);

        $this->actingAs($admin)->putJson(route('pendaftaran.loans.update', $loan), [
            'book_id' => $book->id,
            'borrower_name' => $loan->borrower_name,
            'school' => $loan->school,
            'study_program' => $loan->study_program,
            'email' => $loan->email,
            'phone' => $loan->phone,
            'loan_date' => '2026-11-01',
            'return_date' => '2026-11-08',
            'status' => 'Dipinjam',
        ])->assertUnprocessable();

        $this->assertSame('Dikembalikan', $loan->fresh()->status);
        $this->assertSame(0, $book->fresh()->stock);
    }
}
