<?php

use Illuminate\Database\Seeder;
use App\Models\Role;
use App\Models\User;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $role_admin = Role::where('name', 'admin')->first();
        $role_employee = Role::where('name', 'employee')->first();

        $user = new User();
        $user->name = "admin";
        $user->email = "admin@prueba.com";
        $user->password = bcrypt('12345');
        $user->status = true;
        $user->save();
        $user->roles()->attach($role_admin);

        $user = new User();
        $user->name = "employee";
        $user->email = "employee@prueba.com";
        $user->password = bcrypt('12345');
        $user->status = true;
        $user->save();
        $user->roles()->attach($role_employee);
    }
}
