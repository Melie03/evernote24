<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Todo extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'description', 'due_date', 'note_id', 'assigned_user_id'];

    public function note() {
        return $this->belongsTo(Note::class);
    }

    public function tags() {
        return $this->belongsToMany(Tag::class);
    }
}
