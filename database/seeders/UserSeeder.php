<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
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
        $usuarios = [
            [
                'name' => 'Matías Magliano',
                'email' => 'matias.magliano@mi.unc.edu.ar',
                'email_verified_at' => now(),
                'password' => Hash::make('g9L!F6_dgUjp863'),
                'remember_token' => Str::random(10),
            ],
            [
                'name' => 'Matías Molina',
                'email' => 'matias_molina@mi.unc.edu.ar',
                'email_verified_at' => now(),
                'password' => Hash::make('mmolina'),
                'remember_token' => Str::random(10),
            ],
            [
                'name' => 'Marcelo Núñez',
                'email' => 'm.nunez@mi.unc.edu.ar',
                'email_verified_at' => now(),
                'password' => Hash::make('mnunez'),
                'remember_token' => Str::random(10),
            ],
            [
                'name' => 'Melina Segura',
                'email' => 'melina.segura@dlc-laravel.ddns.net',
                'email_verified_at' => now(),
                'password' => Hash::make('eePhiC1w'),
                'remember_token' => Str::random(10),
            ],
            [
                'name' => 'Martín Segura',
                'email' => 'martin.segura@dlc-laravel.ddns.net',
                'email_verified_at' => now(),
                'password' => Hash::make('Aij7aej8'),
                'remember_token' => Str::random(10),
            ],
            [
                'name' => 'Tomás Segura',
                'email' => 'tomas.segura@dlc-laravel.ddns.net',
                'email_verified_at' => now(),
                'password' => Hash::make('bahPh0ei'),
                'remember_token' => Str::random(10),
            ],
        ];

        foreach($usuarios as $usuario){
            User::create($usuario);
        }
    }
}
