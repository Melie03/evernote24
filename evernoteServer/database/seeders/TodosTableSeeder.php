<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Todo;

class TodosTableSeeder extends Seeder
{
    public function run()
    {
        Todo::create([
            'title' => 'Finish Assignment',
            'description' => 'Need to finish my Laravel assignment by tonight.',
            'due_date' => now()->addDays(1),
            'note_id' => 1,
            'assigned_user_id' => 1
        ]);
    }
}
