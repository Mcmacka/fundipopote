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
    ['email' => 'adminfundipopote@gmail.com'], // The search criteria
    [
        'name' => 'Admin FundiPopote',
        'phone' => '+255700000002',
        'password' => bcrypt('admin@1234'),
        'role' => 'admin',
        'is_active' => 1,
        'email_verified_at' => now(),
    ]
);
    }
}
