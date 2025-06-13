<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'activo'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'activo' => 'boolean',
    ];

    // Constantes
    const ROLE_ADMIN = 'admin';
    const ROLE_ADMINISTRATIVO = 'administrativo';
    const ROLE_INSPECTOR = 'inspector';

    // Relaciones
    public function introducciones()
    {
        return $this->hasMany(Introduccion::class);
    }

    public function redespachos()
    {
        return $this->hasMany(Redespacho::class);
    }

    // Scopes
    public function scopeActivos($query)
    {
        return $query->where('activo', true);
    }

    public function scopeRole($query, $role)
    {
        return $query->where('role', $role);
    }

    // Métodos de rol
    public function esAdmin()
    {
        return $this->role === self::ROLE_ADMIN;
    }

    public function esAdministrativo()
    {
        return $this->role === self::ROLE_ADMINISTRATIVO;
    }

    public function esInspector()
    {
        return $this->role === self::ROLE_INSPECTOR;
    }

    public function puedeEditar()
    {
        return in_array($this->role, [self::ROLE_ADMIN, self::ROLE_ADMINISTRATIVO]);
    }
}