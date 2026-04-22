<?php

namespace App\Models\Inward;

use App\Enums\InwardStatusEnum;
use App\Models\Party;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Inward extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected static function booted()
    {
        static::deleting(function ($inward) {
            $inward->inwardDetails()->each(function ($inwardDetail) {
                $inwardDetail->delete();
            });
        });

        static::restoring(function ($inward) {
            $inward->inwardDetails()->withTrashed()->each(function ($inwardDetail) use ($inward) {
                if ($inwardDetail->deleted_at == $inward->deleted_at) {
                    $inwardDetail->restore();
                }
            });
        });
    }

    protected $fillable = [
        'date',
        'time',
        'supplier_id',
        'qty',
    ];

    protected $casts = [
        'inward_status' => InwardStatusEnum::class,
    ];

    public function inwardDetails()
    {
        return $this->hasMany(InwardDetail::class, 'inward_id');
    }

    public function supplier()
    {
        return $this->belongsTo(Party::class, 'supplier_id', 'id');
    }
}
