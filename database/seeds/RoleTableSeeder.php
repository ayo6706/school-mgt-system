<?php

use Illuminate\Database\Seeder;
use App\Role;

class RoleTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $role_regular_user = new Role;
        $role_regular_user->name = 'courseLecturer';
        $role_regular_user->save();

        $role_admin_user = new Role;
        $role_admin_user->name = 'departmentalOfficer';
        $role_admin_user->save();

        $role_admin_user = new Role;
        $role_admin_user->name = 'collegeOfficer';
        $role_admin_user->save();
    }
}
