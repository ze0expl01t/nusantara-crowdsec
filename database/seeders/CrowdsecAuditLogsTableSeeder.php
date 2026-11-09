<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class CrowdsecAuditLogsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('crowdsec_audit_logs')->delete();
        
        \DB::table('crowdsec_audit_logs')->insert(array (
            0 => 
            array (
                'id' => 1,
                'action' => 'refresh_dashboard_with_charts',
                'target' => 'lapi',
                'actor' => 'admin@smkmuh1blora.sch.id',
                'meta' => '{"total_fetched":0}',
                'created_at' => '2026-11-09 06:07:59',
                'updated_at' => '2026-11-09 06:07:59',
            ),
            1 => 
            array (
                'id' => 2,
                'action' => 'refresh_dashboard_with_charts',
                'target' => 'lapi',
                'actor' => 'admin@smkmuh1blora.sch.id',
                'meta' => '{"total_fetched":0}',
                'created_at' => '2026-11-09 06:08:03',
                'updated_at' => '2026-11-09 06:08:03',
            ),
            2 => 
            array (
                'id' => 3,
                'action' => 'refresh_dashboard_with_charts',
                'target' => 'lapi',
                'actor' => 'admin@smkmuh1blora.sch.id',
                'meta' => '{"total_fetched":0}',
                'created_at' => '2026-11-09 06:08:06',
                'updated_at' => '2026-11-09 06:08:06',
            ),
            3 => 
            array (
                'id' => 4,
                'action' => 'refresh_dashboard_with_charts',
                'target' => 'lapi',
                'actor' => 'admin@smkmuh1blora.sch.id',
                'meta' => '{"total_fetched":1}',
                'created_at' => '2026-11-09 06:15:42',
                'updated_at' => '2026-11-09 06:15:42',
            ),
            4 => 
            array (
                'id' => 5,
                'action' => 'refresh_dashboard_with_charts',
                'target' => 'lapi',
                'actor' => 'admin@smkmuh1blora.sch.id',
                'meta' => '{"total_fetched":0}',
                'created_at' => '2026-11-09 06:28:02',
                'updated_at' => '2026-11-09 06:28:02',
            ),
            5 => 
            array (
                'id' => 6,
                'action' => 'refresh_dashboard_with_charts',
                'target' => 'lapi',
                'actor' => 'admin@smkmuh1blora.sch.id',
                'meta' => '{"total_fetched":36}',
                'created_at' => '2026-11-09 06:32:49',
                'updated_at' => '2026-11-09 06:32:49',
            ),
            6 => 
            array (
                'id' => 7,
                'action' => 'refresh_dashboard',
                'target' => 'lapi',
                'actor' => 'admin@smkmuh1blora.sch.id',
                'meta' => '{"total_fetched":35}',
                'created_at' => '2026-11-09 06:36:16',
                'updated_at' => '2026-11-09 06:36:16',
            ),
            7 => 
            array (
                'id' => 8,
                'action' => 'refresh_dashboard',
                'target' => 'lapi',
                'actor' => 'admin@smkmuh1blora.sch.id',
                'meta' => '{"total_fetched":37}',
                'created_at' => '2026-11-09 06:39:39',
                'updated_at' => '2026-11-09 06:39:39',
            ),
            8 => 
            array (
                'id' => 9,
                'action' => 'refresh_dashboard',
                'target' => 'lapi',
                'actor' => 'admin@smkmuh1blora.sch.id',
                'meta' => '{"total_fetched":37}',
                'created_at' => '2026-11-09 06:40:28',
                'updated_at' => '2026-11-09 06:40:28',
            ),
            9 => 
            array (
                'id' => 10,
                'action' => 'refresh_dashboard',
                'target' => 'lapi',
                'actor' => 'admin@smkmuh1blora.sch.id',
                'meta' => '{"total_fetched":37}',
                'created_at' => '2026-11-09 06:40:31',
                'updated_at' => '2026-11-09 06:40:31',
            ),
            10 => 
            array (
                'id' => 11,
                'action' => 'refresh_dashboard',
                'target' => 'lapi',
                'actor' => 'admin@smkmuh1blora.sch.id',
                'meta' => '{"total_fetched":37}',
                'created_at' => '2026-11-09 06:41:03',
                'updated_at' => '2026-11-09 06:41:03',
            ),
            11 => 
            array (
                'id' => 12,
                'action' => 'refresh_dashboard',
                'target' => 'lapi',
                'actor' => 'admin@smkmuh1blora.sch.id',
                'meta' => '{"total_fetched":37}',
                'created_at' => '2026-11-09 06:41:56',
                'updated_at' => '2026-11-09 06:41:56',
            ),
            12 => 
            array (
                'id' => 13,
                'action' => 'refresh_dashboard',
                'target' => 'lapi',
                'actor' => 'admin@smkmuh1blora.sch.id',
                'meta' => '{"total_fetched":37}',
                'created_at' => '2026-11-09 06:42:59',
                'updated_at' => '2026-11-09 06:42:59',
            ),
            13 => 
            array (
                'id' => 14,
                'action' => 'refresh_dashboard',
                'target' => 'lapi',
                'actor' => 'admin@smkmuh1blora.sch.id',
                'meta' => '{"total_fetched":37}',
                'created_at' => '2026-11-09 06:43:21',
                'updated_at' => '2026-11-09 06:43:21',
            ),
            14 => 
            array (
                'id' => 15,
                'action' => 'refresh_dashboard',
                'target' => 'lapi',
                'actor' => 'admin@smkmuh1blora.sch.id',
                'meta' => '{"total_fetched":37}',
                'created_at' => '2026-11-09 06:44:49',
                'updated_at' => '2026-11-09 06:44:49',
            ),
            15 => 
            array (
                'id' => 16,
                'action' => 'refresh_dashboard',
                'target' => 'lapi',
                'actor' => 'admin@smkmuh1blora.sch.id',
                'meta' => '{"total_fetched":38}',
                'created_at' => '2026-11-09 06:46:11',
                'updated_at' => '2026-11-09 06:46:11',
            ),
            16 => 
            array (
                'id' => 17,
                'action' => 'refresh_dashboard',
                'target' => 'lapi',
                'actor' => 'admin@smkmuh1blora.sch.id',
                'meta' => '{"total_fetched":38}',
                'created_at' => '2026-11-09 06:46:26',
                'updated_at' => '2026-11-09 06:46:26',
            ),
            17 => 
            array (
                'id' => 18,
                'action' => 'refresh_dashboard',
                'target' => 'lapi',
                'actor' => 'admin@smkmuh1blora.sch.id',
                'meta' => '{"total_fetched":38}',
                'created_at' => '2026-11-09 06:47:35',
                'updated_at' => '2026-11-09 06:47:35',
            ),
            18 => 
            array (
                'id' => 19,
                'action' => 'refresh_dashboard',
                'target' => 'lapi',
                'actor' => 'admin@smkmuh1blora.sch.id',
                'meta' => '{"total_fetched":500}',
                'created_at' => '2026-11-09 07:03:16',
                'updated_at' => '2026-11-09 07:03:16',
            ),
            19 => 
            array (
                'id' => 20,
                'action' => 'refresh_dashboard',
                'target' => 'lapi',
                'actor' => 'admin@smkmuh1blora.sch.id',
                'meta' => '{"total_fetched":500}',
                'created_at' => '2026-11-09 07:03:43',
                'updated_at' => '2026-11-09 07:03:43',
            ),
            20 => 
            array (
                'id' => 21,
                'action' => 'refresh_dashboard',
                'target' => 'lapi',
                'actor' => 'admin@smkmuh1blora.sch.id',
                'meta' => '{"total_fetched":500}',
                'created_at' => '2026-11-09 07:04:33',
                'updated_at' => '2026-11-09 07:04:33',
            ),
            21 => 
            array (
                'id' => 22,
                'action' => 'refresh_dashboard',
                'target' => 'lapi',
                'actor' => 'admin@smkmuh1blora.sch.id',
                'meta' => '{"total_fetched":500}',
                'created_at' => '2026-11-09 07:08:47',
                'updated_at' => '2026-11-09 07:08:47',
            ),
            22 => 
            array (
                'id' => 23,
                'action' => 'refresh_dashboard',
                'target' => 'lapi',
                'actor' => 'admin@smkmuh1blora.sch.id',
                'meta' => '{"total_fetched":500}',
                'created_at' => '2026-11-09 07:11:13',
                'updated_at' => '2026-11-09 07:11:13',
            ),
            23 => 
            array (
                'id' => 24,
                'action' => 'refresh_dashboard',
                'target' => 'lapi',
                'actor' => 'admin@smkmuh1blora.sch.id',
                'meta' => '{"total_fetched":500}',
                'created_at' => '2026-11-09 07:11:19',
                'updated_at' => '2026-11-09 07:11:19',
            ),
            24 => 
            array (
                'id' => 25,
                'action' => 'refresh_dashboard',
                'target' => 'lapi',
                'actor' => 'admin@smkmuh1blora.sch.id',
                'meta' => '{"total_fetched":500}',
                'created_at' => '2026-11-09 07:13:58',
                'updated_at' => '2026-11-09 07:13:58',
            ),
            25 => 
            array (
                'id' => 26,
                'action' => 'refresh_dashboard',
                'target' => 'lapi',
                'actor' => 'admin@smkmuh1blora.sch.id',
                'meta' => '{"total_fetched":500}',
                'created_at' => '2026-11-09 07:14:43',
                'updated_at' => '2026-11-09 07:14:43',
            ),
            26 => 
            array (
                'id' => 27,
                'action' => 'refresh_dashboard',
                'target' => 'lapi',
                'actor' => 'admin@smkmuh1blora.sch.id',
                'meta' => '{"total_fetched":500}',
                'created_at' => '2026-11-09 07:14:49',
                'updated_at' => '2026-11-09 07:14:49',
            ),
            27 => 
            array (
                'id' => 28,
                'action' => 'refresh_dashboard',
                'target' => 'lapi',
                'actor' => 'admin@smkmuh1blora.sch.id',
                'meta' => '{"total_fetched":500}',
                'created_at' => '2026-11-09 07:15:13',
                'updated_at' => '2026-11-09 07:15:13',
            ),
            28 => 
            array (
                'id' => 29,
                'action' => 'refresh_dashboard',
                'target' => 'lapi',
                'actor' => 'admin@smkmuh1blora.sch.id',
                'meta' => '{"total_fetched":500}',
                'created_at' => '2026-11-09 07:15:34',
                'updated_at' => '2026-11-09 07:15:34',
            ),
            29 => 
            array (
                'id' => 30,
                'action' => 'refresh_dashboard',
                'target' => 'lapi',
                'actor' => 'admin@smkmuh1blora.sch.id',
                'meta' => '{"total_fetched":500}',
                'created_at' => '2026-11-09 07:16:40',
                'updated_at' => '2026-11-09 07:16:40',
            ),
            30 => 
            array (
                'id' => 31,
                'action' => 'refresh_dashboard',
                'target' => 'lapi',
                'actor' => 'admin@smkmuh1blora.sch.id',
                'meta' => '{"total_fetched":500}',
                'created_at' => '2026-11-09 07:18:39',
                'updated_at' => '2026-11-09 07:18:39',
            ),
            31 => 
            array (
                'id' => 32,
                'action' => 'refresh_dashboard',
                'target' => 'lapi',
                'actor' => 'admin@smkmuh1blora.sch.id',
                'meta' => '{"total_fetched":500}',
                'created_at' => '2026-11-09 07:21:54',
                'updated_at' => '2026-11-09 07:21:54',
            ),
            32 => 
            array (
                'id' => 33,
                'action' => 'refresh_dashboard',
                'target' => 'lapi',
                'actor' => 'admin@smkmuh1blora.sch.id',
                'meta' => '{"total_fetched":500}',
                'created_at' => '2026-11-09 07:22:13',
                'updated_at' => '2026-11-09 07:22:13',
            ),
            33 => 
            array (
                'id' => 34,
                'action' => 'refresh_dashboard',
                'target' => 'lapi',
                'actor' => 'admin@smkmuh1blora.sch.id',
                'meta' => '{"total_fetched":500}',
                'created_at' => '2026-11-09 07:22:28',
                'updated_at' => '2026-11-09 07:22:28',
            ),
            34 => 
            array (
                'id' => 35,
                'action' => 'refresh_dashboard',
                'target' => 'lapi',
                'actor' => 'admin@smkmuh1blora.sch.id',
                'meta' => '{"total_fetched":500}',
                'created_at' => '2026-11-09 07:23:10',
                'updated_at' => '2026-11-09 07:23:10',
            ),
            35 => 
            array (
                'id' => 36,
                'action' => 'refresh_dashboard',
                'target' => 'lapi',
                'actor' => 'admin@smkmuh1blora.sch.id',
                'meta' => '{"total_fetched":500}',
                'created_at' => '2026-11-09 07:23:22',
                'updated_at' => '2026-11-09 07:23:22',
            ),
            36 => 
            array (
                'id' => 37,
                'action' => 'refresh_dashboard',
                'target' => 'lapi',
                'actor' => 'admin@smkmuh1blora.sch.id',
                'meta' => '{"total_fetched":500}',
                'created_at' => '2026-11-09 07:24:26',
                'updated_at' => '2026-11-09 07:24:26',
            ),
            37 => 
            array (
                'id' => 38,
                'action' => 'unban_ip',
                'target' => '104.152.52.115',
                'actor' => 'admin@smkmuh1blora.sch.id',
                'meta' => '{"decision_id":"8571552"}',
                'created_at' => '2026-11-09 07:25:19',
                'updated_at' => '2026-11-09 07:25:19',
            ),
            38 => 
            array (
                'id' => 39,
                'action' => 'refresh_dashboard',
                'target' => 'lapi',
                'actor' => 'admin@smkmuh1blora.sch.id',
                'meta' => '{"total_fetched":500}',
                'created_at' => '2026-11-09 07:26:05',
                'updated_at' => '2026-11-09 07:26:05',
            ),
            39 => 
            array (
                'id' => 40,
                'action' => 'refresh_dashboard',
                'target' => 'lapi',
                'actor' => 'admin@smkmuh1blora.sch.id',
                'meta' => '{"total_fetched":500}',
                'created_at' => '2026-11-09 07:27:48',
                'updated_at' => '2026-11-09 07:27:48',
            ),
            40 => 
            array (
                'id' => 41,
                'action' => 'add_allowlist',
                'target' => '136.226.235.85',
                'actor' => 'admin@smkmuh1blora.sch.id',
                'meta' => NULL,
                'created_at' => '2026-11-09 07:29:42',
                'updated_at' => '2026-11-09 07:29:42',
            ),
            41 => 
            array (
                'id' => 42,
                'action' => 'refresh_dashboard',
                'target' => 'lapi',
                'actor' => 'admin@smkmuh1blora.sch.id',
                'meta' => '{"total_fetched":500}',
                'created_at' => '2026-11-09 07:30:24',
                'updated_at' => '2026-11-09 07:30:24',
            ),
            42 => 
            array (
                'id' => 43,
                'action' => 'refresh_dashboard',
                'target' => 'lapi',
                'actor' => 'admin@smkmuh1blora.sch.id',
                'meta' => '{"total_fetched":500}',
                'created_at' => '2026-11-09 07:30:54',
                'updated_at' => '2026-11-09 07:30:54',
            ),
            43 => 
            array (
                'id' => 44,
                'action' => 'refresh_dashboard',
                'target' => 'lapi',
                'actor' => 'admin@smkmuh1blora.sch.id',
                'meta' => '{"total_fetched":500}',
                'created_at' => '2026-11-09 07:33:03',
                'updated_at' => '2026-11-09 07:33:03',
            ),
            44 => 
            array (
                'id' => 45,
                'action' => 'refresh_dashboard',
                'target' => 'lapi',
                'actor' => 'admin@smkmuh1blora.sch.id',
                'meta' => '{"total_fetched":500}',
                'created_at' => '2026-11-09 07:33:20',
                'updated_at' => '2026-11-09 07:33:20',
            ),
            45 => 
            array (
                'id' => 46,
                'action' => 'refresh_dashboard',
                'target' => 'lapi',
                'actor' => 'admin@smkmuh1blora.sch.id',
                'meta' => '{"total_fetched":500}',
                'created_at' => '2026-11-09 07:42:24',
                'updated_at' => '2026-11-09 07:42:24',
            ),
            46 => 
            array (
                'id' => 47,
                'action' => 'refresh_dashboard',
                'target' => 'lapi',
                'actor' => 'admin@smkmuh1blora.sch.id',
                'meta' => '{"total_fetched":500}',
                'created_at' => '2026-11-09 07:43:04',
                'updated_at' => '2026-11-09 07:43:04',
            ),
            47 => 
            array (
                'id' => 48,
                'action' => 'refresh_dashboard',
                'target' => 'lapi',
                'actor' => 'admin@smkmuh1blora.sch.id',
                'meta' => '{"total_fetched":500}',
                'created_at' => '2026-11-09 07:47:11',
                'updated_at' => '2026-11-09 07:47:11',
            ),
            48 => 
            array (
                'id' => 49,
                'action' => 'refresh_dashboard',
                'target' => 'lapi',
                'actor' => 'admin@smkmuh1blora.sch.id',
                'meta' => '{"total_fetched":500}',
                'created_at' => '2026-11-09 07:51:22',
                'updated_at' => '2026-11-09 07:51:22',
            ),
            49 => 
            array (
                'id' => 50,
                'action' => 'refresh_dashboard',
                'target' => 'lapi',
                'actor' => 'admin@smkmuh1blora.sch.id',
                'meta' => '{"total_fetched":500}',
                'created_at' => '2026-11-09 08:01:41',
                'updated_at' => '2026-11-09 08:01:41',
            ),
            50 => 
            array (
                'id' => 51,
                'action' => 'refresh_dashboard',
                'target' => 'lapi',
                'actor' => 'admin@smkmuh1blora.sch.id',
                'meta' => '{"total_fetched":500}',
                'created_at' => '2026-11-09 08:07:49',
                'updated_at' => '2026-11-09 08:07:49',
            ),
            51 => 
            array (
                'id' => 52,
                'action' => 'refresh_dashboard',
                'target' => 'lapi',
                'actor' => 'admin@smkmuh1blora.sch.id',
                'meta' => '{"total_fetched":500}',
                'created_at' => '2026-11-09 08:10:11',
                'updated_at' => '2026-11-09 08:10:11',
            ),
            52 => 
            array (
                'id' => 53,
                'action' => 'refresh_dashboard',
                'target' => 'lapi',
                'actor' => 'admin@smkmuh1blora.sch.id',
                'meta' => '{"total_fetched":500}',
                'created_at' => '2026-11-09 08:12:21',
                'updated_at' => '2026-11-09 08:12:21',
            ),
            53 => 
            array (
                'id' => 54,
                'action' => 'refresh_dashboard',
                'target' => 'lapi',
                'actor' => 'admin@smkmuh1blora.sch.id',
                'meta' => '{"total_fetched":500}',
                'created_at' => '2026-11-09 08:13:00',
                'updated_at' => '2026-11-09 08:13:00',
            ),
            54 => 
            array (
                'id' => 55,
                'action' => 'refresh_dashboard',
                'target' => 'lapi',
                'actor' => 'admin@smkmuh1blora.sch.id',
                'meta' => '{"total_fetched":500}',
                'created_at' => '2026-11-09 08:44:02',
                'updated_at' => '2026-11-09 08:44:02',
            ),
            55 => 
            array (
                'id' => 56,
                'action' => 'refresh_dashboard',
                'target' => 'lapi',
                'actor' => 'admin@smkmuh1blora.sch.id',
                'meta' => '{"total_fetched":500}',
                'created_at' => '2026-11-09 08:44:22',
                'updated_at' => '2026-11-09 08:44:22',
            ),
            56 => 
            array (
                'id' => 57,
                'action' => 'refresh_dashboard',
                'target' => 'lapi',
                'actor' => 'admin@smkmuh1blora.sch.id',
                'meta' => '{"total_fetched":500}',
                'created_at' => '2026-11-09 08:44:42',
                'updated_at' => '2026-11-09 08:44:42',
            ),
            57 => 
            array (
                'id' => 58,
                'action' => 'refresh_dashboard',
                'target' => 'lapi',
                'actor' => 'admin@smkmuh1blora.sch.id',
                'meta' => '{"total_fetched":500}',
                'created_at' => '2026-11-09 08:46:54',
                'updated_at' => '2026-11-09 08:46:54',
            ),
        ));
        
        
    }
}