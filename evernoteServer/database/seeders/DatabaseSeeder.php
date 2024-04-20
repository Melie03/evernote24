<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        $this->call([
            UsersTableSeeder::class,
            NoteListsTableSeeder::class,
            NotesTableSeeder::class,
            TodosTableSeeder::class,
            TagsTableSeeder::class,
        ]);
    }
}
