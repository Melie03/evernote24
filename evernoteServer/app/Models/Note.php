<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Note extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'description', 'note_list_id'];

    public function noteList() {
        return $this->belongsTo(NoteList::class);
    }

    public function todos() {
        return $this->hasMany(Todo::class);
    }

    public function tags() {
        return $this->belongsToMany(Tag::class);
    }
}

