<?php

use App\Models\Users\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class PermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //Create the roles
        $admin = Role::create(['name' => 'Administrator']);
        $seniorStaff = Role::create(['name' => 'Senior Staff']);
        $trainingTeam = Role::create(['name' => 'Training Team']);
        $webTeam = Role::create(['name' => 'Web Team']);
        $marketingTeam = Role::create(['name' => 'Marketing Team']);
        $certifiedController = Role::create(['name' => 'Certified Controller']);
        $trainee = Role::create(['name' => 'Trainee']);
        $guest = Role::create(['name' => 'Guest']);
        $restricted = Role::create(['name' => 'Restricted']);

        //Create permissions.. this will be a mess :(
        //Settings
        Permission::create(['name' => 'edit settings']);

        //Users
        Permission::create(['name' => 'view users']);
        Permission::create(['name' => 'view user data']);
        Permission::create(['name' => 'edit user data']);
        Permission::create(['name' => 'delete users']);

        //Tickets
        Permission::create(['name' => 'view tickets']);
        Permission::create(['name' => 'reply to tickets']);
        Permission::create(['name' => 'close tickets']);
        Permission::create(['name' => 'edit tickets']);

        //Publications
        Permission::create(['name' => 'edit atc resources']);
        Permission::create(['name' => 'view certified only atc resource']);
        Permission::create(['name' => 'edit policies']);

        //News
        Permission::create(['name' => 'view articles']);
        Permission::create(['name' => 'create articles']);
        Permission::create(['name' => 'edit articles']);
        Permission::create(['name' => 'delete articles']);
        Permission::create(['name' => 'send announcements']);

        //Network
        Permission::create(['name' => 'view network data']);
        Permission::create(['name' => 'edit monitored positions']);
        Permission::create(['name' => 'edit session logs']);

        //Events
        Permission::create(['name' => 'submit event controller application']);
        Permission::create(['name' => 'view events']);
        Permission::create(['name' => 'create event']);
        Permission::create(['name' => 'edit event']);
        Permission::create(['name' => 'delete event']);

        //ActivityBot
        Permission::create(['name' => 'view activity data']);
        Permission::create(['name' => 'process inactivity']);

        //Sync roles and their permissions
        $seniorStaff->syncPermissions(
            [
                'view users',
                'view tickets',
                'reply to tickets',
                'close tickets',
                'edit tickets',
                'edit atc resources',
                'edit policies',
                'view articles',
                'create articles',
                'edit articles',
                'delete articles',
                'send announcements',
                'view network data',
                'edit monitored positions',
                'edit session logs',
                'view events',
                'create event',
                'edit event',
                'delete event',
                'view activity data'
            ]
        );

        /* $trainingTeam->syncPermissions(
            [
            ]
        ); */

        $webTeam->syncPermissions(
            [
                'edit settings',
                'view tickets',
                'reply to tickets',
                'close tickets'
            ]
        );

        $marketingTeam->syncPermissions(
            [
                'view events',
                'create event',
                'edit event',
                'delete event'
            ]
        );

        $certifiedController->syncPermissions(
            [
                'submit event controller application',
                'view certified only atc resource'
            ]
        );


        //Give the bot users the roles they deserve!
        User::find(1)->assignRole('Administrator');
        User::find(2)->assignRole('Administrator');
    }
}
