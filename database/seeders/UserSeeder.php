<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'name'=>'Jay',
            'email'=>'jay@gmail.com',
            'password'=>Hash::make('123456'),
            'status'=>1,
            'role'=>'user',
        ]);
    }
}
