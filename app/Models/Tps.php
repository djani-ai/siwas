<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;


class Tps extends Model
{
    use HasFactory, HasRoles;
    protected $guarded = [];

    public function kel(): BelongsTo
    {
        return $this->belongsTo(Kel::class, 'kel_id');
    }
    public function kec(): BelongsTo
    {
        return $this->belongsTo(Kec::class, 'kec_id');
    }
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    public static function query(): EloquentBuilder
    {
        $role = auth()->user()->roles->pluck('id');
        if (($role->contains(2))) {
            return parent::query()->where('kel_id', auth()->user()->kel->id);
        } else {
            return parent::query();
        }
    }
}
