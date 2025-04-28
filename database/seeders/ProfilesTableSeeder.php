<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProfilesTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('profiles')->insert([
            [
                'prf_name'       => 'Nivel Administrador',
                'prf_status'     => 1,
                'prf_entity'     => 'Admin',
                'prf_created_at' => now(),
                'prf_updated_at' => now(),
            ],
            [
                'prf_name'       => 'Nivel Jurado',
                'prf_status'     => 1,
                'prf_entity'     => 'Judge',
                'prf_created_at' => now(),
                'prf_updated_at' => now(),
            ],
        ]);
    }
}
