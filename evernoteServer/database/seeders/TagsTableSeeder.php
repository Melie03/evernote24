<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Tag;

class TagsTableSeeder extends Seeder
{
    public function run()
    {
        Tag::create([
            'name' => 'Work'
        ]);
        Tag::create([
            'name' => 'Personal'
        ]);
    }
}
