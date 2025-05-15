<?php

namespace Database\Seeders;
use Illuminate\Database\Seeder;
use App\Models\Task;
use App\Models\User;
use Illuminate\Support\Str;

class TaskSeeder extends Seeder
{
    public function run()
    {
        $users = User::where('role', '!=', 'admin')->get();

        if ($users->count() < 2) {
            echo "Not enough users to seed tasks.";
            return;
        }

        foreach ($users as $user) {
            // Hindari menggunakan user yang sama untuk assigned dan created
            $creator = $users->where('id', '!=', $user->id)->random();

            Task::create([
                'id' => (string) Str::uuid(),
                'title' => 'Task for ' . $user->name,
                'description' => 'This is a task for ' . $user->name,
                'assigned_to' => $user->id,
                'created_by' => $creator->id,
                'status' => collect(['pending', 'in_progress', 'done'])->random(),
                'due_date' => now()->addDays(rand(1, 30)),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
