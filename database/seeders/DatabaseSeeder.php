<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Create default admin
       User::updateOrCreate(
    ['email' => 'admin@fundipopote.co.tz'], // The search criteria
    [
        'name' => 'Admin FundiPopote',
        'phone' => '+255700000001',
        'password' => bcrypt('admin@123'),
        'role' => 'admin',
        'is_active' => 1,
        'email_verified_at' => now(),
    ]
);
    }
}
