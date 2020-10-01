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
        //TODO: add this

        //Add to roles
        $seniorStaff = Role::where('name', 'Senior Staff')->first();

        $seniorStaff->givePermissionTo('view applications');
        $seniorStaff->givePermissionTo('view roster admin');
        $seniorStaff->givePermissionTo('edit roster');

        $guest = Role::where('name', 'Guest')->first();
        $guest->givePermissionTo('start applications');
    }
}
