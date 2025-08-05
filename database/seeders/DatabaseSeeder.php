<?php

namespace Database\Seeders;

use App\Models\Admin;
use Illuminate\Database\Seeder;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        Admin::factory()->create([
            'name'     => 'allen',
            'email'    => 'allen@email.com',
            'password' => bcrypt('Password101'),
        ]);

        $this->call([
            LinkSeeder::class,
            TagSeeder::class,
        ]);
    }
}
