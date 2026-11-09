<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('users')->delete();
        
        \DB::table('users')->insert(array (
            0 => 
            array (
                'id' => 1,
                'name' => 'Administrator CrowdSec',
                'email' => 'admin@smkmuh1blora.sch.id',
                'email_verified_at' => NULL,
                'password' => '$2y$12$ThU1ys2N/AkfCejt7U1vcuUrQxmgdoBVXcy2rAPBJQOfpNYfLtReu',
                'remember_token' => NULL,
                'created_at' => '2026-11-09 05:20:06',
                'updated_at' => '2026-11-09 05:20:06',
            ),
        ));
        
        
    }
}