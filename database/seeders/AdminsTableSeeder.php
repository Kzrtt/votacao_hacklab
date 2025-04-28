<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AdminsTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('admins')->insert([
            'adm_fantasy'    => 'Kvrt Dvlpmnt',
            'adm_created_at' => now(),
            'adm_updated_at' => now(),
        ]);
    }
}
