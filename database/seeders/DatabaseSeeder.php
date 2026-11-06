<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Modules\Pendaftaran\Models\Book;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::firstOrCreate(['email' => 'admin@sipustaka.test'], [
            'name' => 'Administrator',
            'role' => 'admin',
            'password' => 'password',
        ]);
        User::firstOrCreate(['email' => 'user@sipustaka.test'], [
            'name' => 'Pengguna SiPustaka',
            'role' => 'user',
            'password' => 'password',
        ]);

        Book::firstOrCreate(['title' => 'Laskar Pelangi'], [
            'author' => 'Andrea Hirata', 'publisher' => 'Bentang Pustaka', 'published_year' => 2005, 'stock' => 8,
        ]);
        Book::firstOrCreate(['title' => 'Bumi Manusia'], [
            'author' => 'Pramoedya Ananta Toer', 'publisher' => 'Hasta Mitra', 'published_year' => 1980, 'stock' => 5,
        ]);
    }
}
