<?php

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class TrainingPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //Applications (user side)
        Permission::create(['name' => 'start applications']);

        //Applications (staff side)
        Permission::create(['name' => 'view applications']);
        Permission::create(['name' => 'interact with applications']);

        //Roster (staff side)
        Permission::create(['name' => 'view roster admin']);
        Permission::create(['name' => 'edit roster']);

        //Training admin
        //Instructing
        Permission::create(['name' => 'view instructing admin']);
        Permission::create(['name' => 'edit instructors']);
        Permission::create(['name' => 'edit students']);
        Permission::create(['name' => 'edit training records']);
        Permission::create(['name' => 'assign instructor to student']);
        Permission::create(['name' => 'edit student status labels']);
        Permission::create(['name' => 'edit training sessions']);
        Permission::create(['name' => 'edit ots sessions']);

        //Add to roles
        $seniorStaff = Role::where('name', 'Senior Staff')->first();

        $seniorStaff->givePermissionTo('view applications');
        $seniorStaff->givePermissionTo('view roster admin');
        $seniorStaff->givePermissionTo('edit roster');
        $seniorStaff->givePermissionTo('view instructing admin');
        $seniorStaff->givePermissionTo('edit instructors');
        $seniorStaff->givePermissionTo('edit students');
        $seniorStaff->givePermissionTo('edit training records');
        $seniorStaff->givePermissionTo('assign instructor to student');
        $seniorStaff->givePermissionTo('edit student status labels');
        $seniorStaff->givePermissionTo('edit training sessions');
        $seniorStaff->givePermissionTo('edit ots sessions');

        $assessor = Role::whereName('Assessor')->first();
        $assessor->givePermissionTo('edit ots sessions');
        $assessor->givePermissionTo('edit roster');

        $instructor = Role::whereName('Instructor')->first();
        $instructor->givePermissionTo('view instructing admin');
        $instructor->givePermissionTo('edit training records');
        $instructor->givePermissionTo('assign instructor to student');
        $instructor->givePermissionTo('edit training sessions');
        $instructor->givePermissionTo('view roster admin');
        $instructor->givePermissionTo('edit roster');

        $guest = Role::where('name', 'Guest')->first();
        $guest->givePermissionTo('start applications');
    }
}
