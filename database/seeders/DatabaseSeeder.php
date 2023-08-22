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
        $admin = User::create([
            'email' => 'dev@wilo.com',
            'password' => bcrypt('secret'),
            'name' => 'Developer'
        ]);

        $this->call(ServerMqttSeeder::class);
        $this->call(PermissionSeeder::class);

        $admin->assignRole(1);
    }
}
