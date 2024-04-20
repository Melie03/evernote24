<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Note;

class NotesTableSeeder extends Seeder
{
    public function run()
    {
        Note::create([
            'title' => 'First Note',
            'description' => 'This is the description of my first note.',
            'note_list_id' => 1 // Ensure this list ID exists in your database
        ]);
    }
}
