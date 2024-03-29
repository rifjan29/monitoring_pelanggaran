<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $akun = ['admin','pondok','mts','ma','smk'];

        for ($i=0; $i < count($akun); $i++) {
            $user = new User;
            $user->name = $akun[$i];
            $user->email = $akun[$i].'@mail.com';
            $user->password = Hash::make('password');
            $user->save();

            $user->assignRole($akun[$i]);
        }

    }
}
