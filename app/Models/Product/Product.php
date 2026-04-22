<?php

namespace App\Models\Product;

use App\Models\Party;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use App\Models\Product\Brand\ProductBrand;
use App\Models\Product\Variant\ProductVariant;
use App\Models\Product\Category\ProductCategory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Product extends Model implements HasMedia
{
    use HasFactory;
    use InteractsWithMedia;
    use SoftDeletes;

    public static function boot(): void
    {
        parent::boot();
        static::creating(function ($model) {
            $slug = Str::slug($model->name ?? $model->product_name);
            $count = static::whereRaw("slug RLIKE '^{$slug}(-[0-9]+)?$'")->count();
            $model->slug = $count ? "{$slug}-{$count}" : $slug;
        });
    }

    protected static function booted()
    {
        static::deleting(function ($product) {
            $product->variants()->each(function ($variant) {
                $variant->delete();
            });
        });

        static::restoring(function ($product) {
            $product->variants()->withTrashed()->each(function ($variant) use ($product) {
                if ($variant->deleted_at == $product->deleted_at) {
                    $variant->restore();
                }
            });
        });
    }

    protected $fillable = [
        'product_name',
        'product_urdu_name',
        'slug',
        'code',
        'description',
        'is_manage_variants',
        'is_fixed_asset',
        'is_featured',
        'new_arrival',
        'best_selling_product',
        'category_id',
        'brand_id',
        'supplier_id',
        'user_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function category()
    {
        return $this->belongsTo(ProductCategory::class, 'category_id', 'id');
    }

    public function brand()
    {
        return $this->belongsTo(ProductBrand::class, 'brand_id', 'id');
    }

    public function supplier()
    {
        return $this->belongsTo(Party::class, 'supplier_id', 'id');
    }

    public function variants()
    {
        return $this->hasMany(ProductVariant::class, 'product_id');
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('pro_thumbnail_image')->singleFile();
    }
}
