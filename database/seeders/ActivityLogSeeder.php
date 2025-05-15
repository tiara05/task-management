<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ActivityLog;
use App\Models\User;
use Illuminate\Support\Str;

class ActivityLogSeeder extends Seeder
{
    public function run()
    {
        $users = User::all();

        foreach ($users as $user) {
            ActivityLog::create([
                'user_id' => $user->id,
                'action' => collect(['create_user', 'update_task', 'delete_user'])->random(),
                'description' => 'Log by ' . $user->name,
                'logged_at' => now()->subDays(rand(0, 7)),
            ]);
        }
    }
}
