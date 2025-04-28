<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run()
    {
        $this->call([
            ProfilesTableSeeder::class,
            PersonsTableSeeder::class,
            AdminsTableSeeder::class,
            UsersTableSeeder::class,
            UserRepresentedAgentsTableSeeder::class,
        ]);
    }

}
