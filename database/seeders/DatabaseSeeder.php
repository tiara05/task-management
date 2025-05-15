<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Task;
use App\Models\ActivityLog;
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
            UserSeeder::class,
            TaskSeeder::class,
            ActivityLogSeeder::class,
        ]);
    }

}
