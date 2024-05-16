<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Todo extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'description', 'due_date', 'note_id', 'assigned_user_id', 'completed'];

    public function note() {
        return $this->belongsTo(Note::class);
    }

    public function tags() {
        return static::belongsToMany(Tag::class, 'todo_tag', 'todo_id', 'tag_id');
    }
}
