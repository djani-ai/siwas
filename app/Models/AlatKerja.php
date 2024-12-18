<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Spatie\Permission\Traits\HasRoles;

class AlatKerja extends Model
{
    use HasFactory, HasRoles;
    protected $guarded = [];

    public function kel(): BelongsTo
    {
        return $this->belongsTo(Kel::class, 'kel_id');
    }
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function tps(): BelongsTo
    {
        return $this->belongsTo(Tps::class, 'tps_id');
    }
    public static function query(): EloquentBuilder
    {
        $role = auth()->user()->roles->pluck('id');
        if (($role->contains(2))) {
            return parent::query()
                ->where('kel_id', auth()->user()->kel_id)
                ->whereNull('tps_id');
        } else if (($role->contains(4))) {
            return parent::query()->where('tps_id', auth()->user()->tps_id);
        } else {
            return parent::query();
        }
    }
}
