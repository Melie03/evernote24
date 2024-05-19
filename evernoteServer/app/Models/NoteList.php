<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NoteList extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'user_id'];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function notes() {
        return $this->hasMany(Note::class);
    }
    public function shared()
    {
        return static::belongsToMany(NoteList::class, 'note_list_shared',  'note_list_id','user_id');
    }
    public function sharedUsers()
    {
        return static::belongsToMany(User::class, 'note_list_shared',  'note_list_id','user_id');
    }
}
