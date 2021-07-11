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
            'id'               => 1,
            'sys_name'         => 'Gander Oceanic Core',
            'release'          => 'DEV',
            'sys_build'        => 'DEV',
            'copyright_year'   => 'NONE',
            'banner'           => '',
            'bannerLink'       => '',
            'bannerMode'       => '',
            'emailfirchief'    => 'webmaster@ganderoceanic.ca',
            'emaildepfirchief' => 'webmaster@ganderoceanic.ca',
            'emailcinstructor' => 'webmaster@ganderoceanic.ca',
            'emaileventc'      => 'webmaster@ganderoceanic.ca',
            'emailfacilitye'   => 'webmaster@ganderoceanic.ca',
            'emailwebmaster'   => 'webmaster@ganderoceanic.ca',
        ]);

        DB::table('users')->insert([
            'id'            => 1,
            'fname'         => 'System',
            'lname'         => 'User',
            'email'         => 'no-reply@ganderoceanic.ca',
            'display_fname' => 'System',
        ]);

        DB::table('users')->insert([
            'id'            => 2,
            'fname'         => 'Roster',
            'lname'         => 'Placeholder',
            'email'         => 'no-reply@ganderoceanic.ca',
            'display_fname' => 'Roster',
        ]);

        DB::table('staff_groups')->insert([
            'id'                  => 1,
            'name'                => 'Senior Staff',
            'slug'                => 'seniorstaff',
            'description'         => 'CZQO\'s executive team oversees FIR operations',
            'can_receive_tickets' => true,
        ]);

        DB::table('staff_groups')->insert([
            'id'                  => 2,
            'name'                => 'Web Team',
            'slug'                => 'web',
            'description'         => 'Team responsible for developing CZQO\'s web precense',
            'can_receive_tickets' => true,
        ]);

        DB::table('staff_groups')->insert([
            'id'                  => 3,
            'name'                => 'Marketing Team',
            'slug'                => 'marketing',
            'description'         => 'Team responsible for marketing CZQO',
            'can_receive_tickets' => true,
        ]);

        DB::table('staff_member')->insert([
            'group'       => 'exec',
            'position'    => 'FIR Chief',
            'group_id'    => 1,
            'description' => 'Ensures that CZQO is running optimally. In charge of day to day operations',
            'email'       => 'chief@ganderoceanic.ca',
            'shortform'   => 'firchief',
        ]);

        DB::table('staff_member')->insert([
            'group' => 'exec',

            'position'    => 'Deputy FIR Chief',
            'group_id'    => 1,
            'description' => 'Assistant to the FIR Chief.',
            'email'       => 'deputy@ganderoceanic.ca',
            'shortform'   => 'dfirchief',
        ]);

        DB::table('staff_member')->insert([
            'group' => 'exec',

            'position'    => 'Chief Instructor',
            'group_id'    => 1,
            'description' => 'Manages the CZQO training program, including the talented team of Gander instructors.',
            'email'       => 'training@ganderoceanic.ca',
            'shortform'   => 'cinstructor',
        ]);

        DB::table('staff_member')->insert([
            'group' => 'exec',

            'position'    => 'Events and Marketing Director',
            'group_id'    => 1,
            'description' => 'Devises awesome events for the Gander Oceanic FIR',
            'email'       => 'events@ganderoceanic.ca',
            'shortform'   => 'eventsmarketing',
        ]);

        DB::table('staff_member')->insert([
            'group' => 'exec',

            'position'    => 'Facility Engineer',
            'group_id'    => 1,
            'description' => 'Manages and develops sector files and the Euroscope package for Gander Oceanic.',
            'email'       => 'engineer@ganderoceanic.ca',
            'shortform'   => 'fengineer',

        ]);

        $this->call([
            PermissionsSeeder::class,
            TrainingPermissionsSeeder::class,
            StudentStatusLabelsSeeder::class,
        ]);
    }
}
