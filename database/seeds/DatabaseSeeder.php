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
            'emailfirchief' => 'webmaster@ganderoceanic.com',
            'emaildepfirchief' => 'webmaster@ganderoceanic.com',
            'emailcinstructor' => 'webmaster@ganderoceanic.com',
            'emaileventc' => 'webmaster@ganderoceanic.com',
            'emailfacilitye' => 'webmaster@ganderoceanic.com',
            'emailwebmaster' => 'webmaster@ganderoceanic.com',
        ]);

        DB::table('users')->insert([
            'id' => 1,
            'fname' => 'System',
            'lname' => 'User',
            'email' => 'no-reply@ganderoceanic.com',
            'display_fname' => 'System',
        ]);

        DB::table('users')->insert([
            'id' => 2,
            'fname' => 'Roster',
            'lname' => 'Placeholder',
            'email' => 'no-reply@ganderoceanic.com',
            'display_fname' => 'Roster',
        ]);

        DB::table('staff_groups')->insert([
            'id' => 1,
            'name' => 'Senior Staff',
            'slug' => 'seniorstaff',
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
            'group' => 'exec',
           'position' => 'FIR Chief',
           'group_id' => 1,
           'description' => 'Ensures that CZQO is running optimally. In charge of day to day operations',
            'email' => 'chief@ganderoceanic.com',
            'shortform' => 'firchief',
        ]);

        DB::table('staff_member')->insert([
            'group' => 'exec',

            'position' => 'Deputy FIR Chief',
            'group_id' => 1,
            'description' => 'Assistant to the FIR Chief.',
            'email' => 'deputy@ganderoceanic.com',
            'shortform' => 'dfirchief',
        ]);

        DB::table('staff_member')->insert([
            'group' => 'exec',

            'position' => 'Chief Instructor',
            'group_id' => 1,
            'description' => 'Manages the CZQO training program, including the talented team of Gander instructors.',
            'email' => 'training@ganderoceanic.com',
            'shortform' => 'cinstructor',
        ]);

        DB::table('staff_member')->insert([
            'group' => 'exec',

            'position' => 'Events and Marketing Director',
            'group_id' => 1,
            'description' => 'Devises awesome events for the Gander Oceanic FIR',
            'email' => 'events@ganderoceanic.com',
            'shortform' => 'eventsmarketing',
        ]);

        DB::table('staff_member')->insert([
            'group' => 'exec',

            'position' => 'Facility Engineer',
            'group_id' => 1,
            'description' => 'Manages and develops sector files and the Euroscope package for Gander Oceanic.',
            'email' => 'engineer@ganderoceanic.com',
            'shortform' => 'fengineer',

        ]);
    }
}
