<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $table = 'user';
    protected $primaryKey = 'ID';
    const UPDATED_AT = null;

    protected $fillable = [
        'Nama',
        'Email',
        'password',
        'umur',
        'tempat_lahir',
        'phone',
        'phone_keluarga',
        'alamat',
        'avatar',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function getAvatarUrlAttribute(): string
    {
        if (!empty($this->avatar) && file_exists(public_path('uploads/' . basename($this->avatar)))) {
            return asset('uploads/' . basename($this->avatar));
        }

        $name = $this->Nama ?? 'User';
        return 'https://ui-avatars.com/api/?name=' . urlencode($name) . '&background=002D72&color=fff&size=200';
    }

    public function getMemberSinceAttribute(): string
    {
        if ($this->created_at) {
            return $this->created_at->format('M Y');
        }
        return 'Jan 2024';
    }
}
