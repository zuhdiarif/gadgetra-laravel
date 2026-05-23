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

    public function isAdmin(): bool
    {
        return $this->Email === 'admin@gadgetra.com';
    }

    public static function addUser(string $email, string $password): self
    {
        return self::create([
            'Email' => $email,
            'password' => \Illuminate\Support\Facades\Hash::make($password, ['rounds' => 12]),
        ]);
    }

    public function updateProfile(array $data): bool
    {
        return $this->update([
            'Nama' => $data['nama'] ?? $this->Nama,
            'umur' => (isset($data['umur']) && $data['umur'] !== '') ? $data['umur'] : null,
            'tempat_lahir' => (isset($data['tempat_lahir']) && $data['tempat_lahir'] !== '') ? $data['tempat_lahir'] : null,
            'phone' => (isset($data['phone']) && $data['phone'] !== '') ? $data['phone'] : null,
            'phone_keluarga' => (isset($data['phone_keluarga']) && $data['phone_keluarga'] !== '') ? $data['phone_keluarga'] : null,
            'alamat' => (isset($data['alamat']) && $data['alamat'] !== '') ? $data['alamat'] : null,
        ]);
    }

    public function uploadAvatarFile($file): bool
    {
        if (!empty($this->avatar)) {
            $oldPath = public_path('uploads/' . basename($this->avatar));
            if (file_exists($oldPath)) {
                unlink($oldPath);
            }
        }

        $filename = 'profile_' . substr(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME), 0, 8)
            . '_' . time() . '_' . bin2hex(random_bytes(4))
            . '.' . $file->getClientOriginalExtension();

        $file->move(public_path('uploads'), $filename);

        return $this->update(['avatar' => 'uploads/' . $filename]);
    }
}

