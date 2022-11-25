<?php

use Illuminate\Database\Seeder;
use App\User;
use App\Role;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = new User;
        $user->name = 'Princewill';
        $user->email = 'lecturer@example.com';
        $user->password = bcrypt('lecturer');
        $user->save();
        $user->roles()->attach(Role::where('name', 'courseLecturer')->first());

        $admin = new User;
        $admin->name = 'Departmental Officer';
        $admin->email = 'departmentalOfficer@example.com';
        $admin->password = bcrypt('departmentalofficer');
        $admin->save();
        $admin->roles()->attach(Role::where('name', 'departmentalOfficer')->first());

        $admin = new User;
        $admin->name = 'College Officer';
        $admin->email = 'collegeOfficer@example.com';
        $admin->password = bcrypt('collegeofficer');
        $admin->save();
        $admin->roles()->attach(Role::where('name', 'collegeofficer')->first());
    }
}
