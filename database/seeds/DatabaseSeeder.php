<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        DB::table('core_info')->insert([
            'id' => 1,
            'sys_name' => 'Gander Oceanic Core',
            'release' => 'DEV',
            'sys_build' => 'DEV',
            'copyright_year' => 'NONE',
            'banner' => '',
            'bannerLink' => '',
            'bannerMode' => '',
            /*'emailfirchief' => 'firchief@czqo.vatcan.ca',
            'emaildepfirchief' => 'deputyfirchief@czqo.vatcan.ca',
            'emailcinstructor' => 'chiefinstructor@czqo.vatcan.ca',
            'emaileventc' => 'eventcoordinator@czqo.vatcan.ca',
            'emailfacilitye' => 'facilityengineer@czqo.vatcan.ca',
            'emailwebmaster' => 'webmaster@czqo.vatcan.ca'*/
            'emailfirchief' => 'webmaster@czqo.vatcan.ca',
            'emaildepfirchief' => 'webmaster@czqo.vatcan.ca',
            'emailcinstructor' => 'webmaster@czqo.vatcan.ca',
            'emaileventc' => 'webmaster@czqo.vatcan.ca',
            'emailfacilitye' => 'webmaster@czqo.vatcan.ca',
            'emailwebmaster' => 'webmaster@czqo.vatcan.ca',
        ]);

        DB::table('users')->insert([
            'id' => 1,
            'fname' => 'System',
            'lname' => 'User',
            'email' => 'no-reply@czqo.vatcan.ca',
            'permissions' => 4,
            'display_fname' => 'System',
        ]);

        DB::table('users')->insert([
            'id' => 2,
            'fname' => 'Roster',
            'lname' => 'Placeholder',
            'email' => 'no-reply@czqo.vatcan.ca',
            'permissions' => 1,
            'display_fname' => 'Roster',
        ]);

        DB::table('staff_groups')->insert([
            'id' => 1,
            'name' => 'Executive Team',
            'slug' => 'executive',
            'description' => 'CZQO\'s executive team oversees FIR operations',
            'can_receive_tickets' => true,
        ]);

        DB::table('staff_groups')->insert([
            'id' => 2,
            'name' => 'Web Team',
            'slug' => 'web',
            'description' => 'Team responsible for developing CZQO\'s web precense',
            'can_receive_tickets' => true,
        ]);

        DB::table('staff_groups')->insert([
            'id' => 3,
            'name' => 'Marketing Team',
            'slug' => 'marketing',
            'description' => 'Team responsible for marketing CZQO',
            'can_receive_tickets' => true,
        ]);

        DB::table('staff_member')->insert([
           'position' => 'FIR Chief',
           'group_id' => 1,
           'description' => 'Ensures that CZQO is running optimally. In charge of day to day operations',
            'email' => 'chief@czqo.vatcan.ca',
            'shortform' => 'firchief',
        ]);

        DB::table('staff_member')->insert([
            'position' => 'Deputy FIR Chief',
            'group_id' => 1,
            'description' => 'Assistant to the FIR Chief.',
            'email' => 'deputy@czqo.vatcan.ca',
            'shortform' => 'dfirchief',
        ]);

        DB::table('staff_member')->insert([
            'position' => 'Chief Instructor',
            'group_id' => 1,
            'description' => 'Manages the CZQO training program, including the talented team of Gander instructors.',
            'email' => 'training@czqo.vatcan.ca',
            'shortform' => 'cinstructor',
        ]);

        DB::table('staff_member')->insert([
            'position' => 'Events and Marketing Director',
            'group_id' => 1,
            'description' => 'Devises awesome events for the Gander Oceanic FIR',
            'email' => 'events@czqo.vatcan.ca',
            'shortform' => 'eventsmarketing',
        ]);

        DB::table('staff_member')->insert([
            'position' => 'Facility Engineer',
            'group_id' => 1,
            'description' => 'Manages and develops sector files and the Euroscope package for Gander Oceanic.',
            'email' => 'engineer@czqo.vatcan.ca',
            'shortform' => 'fengineer',

        ]);
    }
}
