<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    public function run()
    {
        // 1. Buscar o person pelo e-mail que você seedou antes
        $personId = DB::table('persons')
            ->where('pes_email', 'felipe@imaxis.com.br')
            ->value('pes_id');

        // 2. Buscar o profile pela entidade 'Admin' (ou outro critério único)
        $profileId = DB::table('profiles')
            ->where('prf_entity', 'Admin')
            ->value('prf_id');

        // 3. Inserir o usuário relacionando as FKs encontradas
        DB::table('users')->insert([
            'usr_email'         => 'admin@exemplo.com',
            'usr_password'      => Hash::make('sua_senha_segura'),
            'usr_level'         => 'admin',          // ou outro valor de nível que você use
            'usr_super' => 1,
            'persons_pes_id'    => $personId,
            'profiles_prf_id'   => $profileId,
            'usr_created_at'    => now(),
            'usr_updated_at'    => now(),
        ]);
    }
}
