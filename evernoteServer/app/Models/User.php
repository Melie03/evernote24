<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Relations\HasMany;
use PHPOpenSourceSaver\JWTAuth\Contracts\JWTSubject;

class User extends  Authenticatable implements JWTSubject
{
    use HasFactory;

    protected $fillable = ['first_name', 'last_name', 'email', 'password', 'image'];

    public function noteLists() {
        return $this->hasMany(NoteList::class);
    }

    public function todos() {
        return $this->hasMany(Todo::class, 'assigned_user_id');
    }
    public function shared()
    {
        return static::belongsToMany(NoteList::class, 'note_list_shared', 'user_id', 'note_list_id');
    }
    /* ---- auth JWT ---- */
    /**
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }
    /**
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return ['user' => ['id' => $this->id]];
    }
}
?>
