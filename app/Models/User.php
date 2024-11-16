<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
// implements FilamentUser
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

    protected $fillable = [
        'id',
        'name',
        'email',
        'password',
        'alamat',
        'kec_id',
        'kel_id',
        'image',
        'ttd',
        'tps_id',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];


    public function kel()
    {
        return $this->belongsTo(Kel::class, 'kel_id');
    }

    public function lhps()
    {
        return $this->hasManyThrough(Lhp::class, Kel::class, 'id', 'kel_id', 'kel_id', 'id');
    }

    public function kec(): BelongsTo
    {
        return $this->belongsTo(Kec::class, 'kec_id');
    }

    public function tps(): BelongsTo
    {
        return $this->belongsTo(Tps::class, 'tps_id');
    }

    // public function canAccessPanel(Panel $panel): bool
    // {
    //     return str_ends_with($this->email, '@gmail.com') && $this->hasVerifiedEmail();
    // }

}
