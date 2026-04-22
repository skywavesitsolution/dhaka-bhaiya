<?php

namespace App\Models\Product\Brand;

use App\Models\User;
use Illuminate\Support\Str;
use App\Models\Product\Product;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProductBrand extends Model
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

    public function products()
    {
        return $this->hasMany(Product::class, 'brand_id');
    }
}
