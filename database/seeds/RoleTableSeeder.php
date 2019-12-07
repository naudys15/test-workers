<?php

use Illuminate\Database\Seeder;
use App\Models\Role;

class RoleTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $rol = new Role();
        $rol->name = "admin";
        $rol->description = "administrador";
        $rol->save();

        $rol = new Role();
        $rol->name = "employee";
        $rol->description = "empleado";
        $rol->save();
    }
}
