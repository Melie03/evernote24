<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\NoteList;

class NoteListsTableSeeder extends Seeder
{
    public function run()
    {
        NoteList::create([
            'name' => 'My First List',
            'user_id' => 1
        ]);
    }
}
