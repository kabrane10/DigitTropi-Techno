<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Admin extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $guard = 'admin';

    protected $fillable = [
        'nom', 'email', 'password', 'role_id', 'avatar', 
        'telephone', 'est_actif', 'derniere_connexion'
    ];

    protected $hidden = ['password', 'remember_token'];

    protected $casts = [
        'est_actif' => 'boolean',
        'derniere_connexion' => 'datetime'
    ];

    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function hasPermission($permission)
    {
        return $this->role->permissions->contains('slug', $permission);
    }
}