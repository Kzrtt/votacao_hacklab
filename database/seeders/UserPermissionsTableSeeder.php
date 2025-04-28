<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserPermissionsTableSeeder extends Seeder
{
    public function run()
    {
        $userId  = 1;
        $areas   = ['Profile', 'Person', 'User'];
        $actions = ['Consult', 'Insert', 'Edit', 'Delete'];

        $rows = [];
        foreach ($areas as $area) {
            foreach ($actions as $action) {
                $rows[] = [
                    'usp_area'      => $area,
                    'usp_action'    => $action,
                    'users_usr_id'  => $userId,
                ];
            }
        }

        DB::table('user_permissions')->insert($rows);
    }
}
