<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::factory()->count(50)->create();

        // User::create([
        //     'email'=>'admin@radiologycheck.com',
        //     'password' => Hash::make('admin@123'),
        //     'first_name' => 'admin',
        //     'last_name' => 'radiology',
        //     'is_admin' => 'true'
        // ]);
    }
}
