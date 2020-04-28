<?php

use Illuminate\Database\Seeder;
use App\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class SecureSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'name' => 'Julio',
            'surname' => 'Potestad',
            'role' => 'ROLE_ADMIN',
            'email' => 'julio@home.com',
            'password' => Hash::make('admin*123'),
            'api_token' => Str::random(80),
        ]);
        for($i=0;$i<3;$i++) {
            User::create([
                'name' => Str::random(10),
                'surname' => Str::random(10),
                'role' => 'ROLE_USER',
                'email' => Str::random(5).'@home.com',
                'password' => Hash::make('admin*123'),
                'api_token' => Str::random(80),
            ]);   
        }
    }
}
