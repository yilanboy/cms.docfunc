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
            Admin::query()->create([
                'name'              => config('admin.name'),
                'email'             => config('admin.email'),
                'password'          => bcrypt(config('admin.password')),
                'email_verified_at' => now(),
                'remember_token'    => null,
                'created_at'        => now(),
                'updated_at'        => now(),
            ]);

            return;
        }

        throw new Exception('Admin name, email and password are required.');
    }
}
