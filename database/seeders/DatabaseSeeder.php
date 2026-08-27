<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@perpus.test'],
            ['name' => 'Administrator', 'password' => Hash::make('password')],
        );

        foreach (['Umum', 'Fiksi', 'Non-Fiksi', 'Teknologi', 'Sejarah'] as $name) {
            Category::firstOrCreate(['name' => $name]);
        }

        $this->call([
            BookSeeder::class,
            MemberSeeder::class,
        ]);
    }
}
