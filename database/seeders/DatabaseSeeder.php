<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'email' => 'dev@wilo.com',
            'password' => bcrypt('secret'),
            'name' => 'Developer'
        ]);

        // \App\Models\User::factory(500)->create();
    }
}
