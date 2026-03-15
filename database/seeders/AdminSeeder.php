<?php

namespace Database\Seeders;

use App\Models\Web\Admin;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        $email = config('app.admin_seed_email');
        $password = config('app.admin_seed_password');

        if (! $email || ! $password) {
            $this->command->warn('ADMIN_SEED_EMAIL or ADMIN_SEED_PASSWORD not set in .env — skipping.');

            return;
        }

        Admin::updateOrCreate(
            ['email' => $email],
            [
                'name'     => 'Admin',
                'email'    => $email,
                'password' => Hash::make($password),
            ]
        );

        $this->command->info("Admin [{$email}] created/updated successfully.");
    }
}
