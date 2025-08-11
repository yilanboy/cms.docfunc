<?php

namespace Database\Seeders;

use App\Models\Admin;
use Exception;
use Illuminate\Database\Seeder;

class AdminSeeder extends Seeder
{
    /**
     * @throws Exception
     */
    public function run(): void
    {
        if (config('admin.name') && config('admin.email') && config('admin.password')) {
            Admin::factory()->create([
                'name'     => config('admin.name'),
                'email'    => config('admin.email'),
                'password' => bcrypt(config('admin.password')),
            ]);

            return;
        }

        throw new Exception('Admin name, email and password are required.');
    }
}
