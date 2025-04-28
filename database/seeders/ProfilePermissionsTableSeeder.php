<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProfilePermissionsTableSeeder extends Seeder
{
    public function run()
    {
        $profileId = 5;

        $areas   = ['Profile', 'Person', 'User'];
        $actions = ['Consult', 'Insert', 'Edit', 'Delete'];

        $rows = [];

        foreach ($areas as $area) {
            foreach ($actions as $action) {
                $rows[] = [
                    'prp_area'          => $area,
                    'prp_action'        => $action,
                    'profiles_prf_id'   => $profileId,
                ];
            }
        }

        DB::table('profile_permissions')->insert($rows);
    }
}
