<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PersonsTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('persons')->insert([
            'pes_name'       => 'Felipe Kurt Pohling',
            'pes_email'      => 'felipe@imaxis.com.br',
            'pes_function'   => 'Organizador',
            'pes_created_at' => now(),
            'pes_updated_at' => now(),
        ]);
    }
}
