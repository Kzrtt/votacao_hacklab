<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserRepresentedAgentsTableSeeder extends Seeder
{
    public function run()
    {
        // pega o ID do admin “Kvrt Dvlpmnt”
        $adminId = DB::table('admins')
            ->where('adm_fantasy', 'Kvrt Dvlpmnt')
            ->value('adm_id');

        // pega o ID do usuário default (o que você seedou por último)
        $userId = DB::table('users')
            ->where('usr_email', 'admin@exemplo.com')
            ->value('usr_id');

        // insere no relacionamento polimórfico
        DB::table('user_represented_agents')->insert([
            'ura_type'             => \App\Models\Admin::class,
            'represented_agent_id' => $adminId,
            'users_usr_id'         => $userId,
        ]);
    }
}
