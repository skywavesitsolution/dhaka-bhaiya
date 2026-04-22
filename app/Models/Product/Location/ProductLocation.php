<?php

namespace App\Models\Product\Location;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use App\Models\Product\Variant\ProductVariant;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProductLocation extends Model
{
    use HasFactory;

    public static function boot(): void
    {
        parent::boot();
        static::creating(function ($model) {
            $slug = Str::slug($model->name ?? $model->title);
            $count = static::whereRaw("slug RLIKE '^{$slug}(-[0-9]+)?$'")->count();
            $model->slug = $count ? "{$slug}-{$count}" : $slug;
        });
    }

    protected $fillable = [
        'name',
        'slug',
        'user_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function variantLocations()
    {
        return $this->hasMany(ProductVariantLocation::class, 'location_id');
    }
}
