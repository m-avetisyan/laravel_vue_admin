<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            'name' => 'Miqo',
            'email' => 'micavetisyan@yandex.ru',
            'password' => Hash::make('123456'),
            'role' => 'superadmin',
            'status' => true,
            'auth_token' => Str::random(),
            'token_generated' => date('Y-m-d H:i:s', time()),
        ]);
    }
}
