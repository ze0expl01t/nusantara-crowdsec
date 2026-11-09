<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class CrowdsecWhitelistedIpsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('crowdsec_whitelisted_ips')->delete();
        
        \DB::table('crowdsec_whitelisted_ips')->insert(array (
            0 => 
            array (
                'id' => 1,
                'ip' => '136.226.235.85',
                'label' => 'Ruhan',
                'note' => NULL,
                'expires_at' => NULL,
                'created_at' => '2026-11-09 07:29:42',
                'updated_at' => '2026-11-09 07:29:42',
            ),
        ));
        
        
    }
}