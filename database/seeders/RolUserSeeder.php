<?php

namespace Database\Seeders;

use App\Models\Rol;
use App\Models\User;
use Illuminate\Database\Seeder;

class RolUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $roles = Rol::all();
        User::all()->each(function ($user) use ($roles){
            $user->roles()->attach(
                $roles->where('nombre', 'sist-admin')->pluck('id')
            );
        });
    }
}
