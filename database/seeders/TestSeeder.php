<?php

namespace Database\Seeders;

use App\Enums\UserType;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $admin = User::query()->create([
            'firstname' => 'Test',
            'lastname' => 'Admin',
            'contact' => '09123456789',
            'middlename' => '',
            'user_type' => UserType::ADMIN,
            'email' => 'test@mrii.com',
            'username' => 'test-admin-mrii',
            'branch_id' => 1,
            'password' => bcrypt('testpassword'),
        ]);
    }
}
