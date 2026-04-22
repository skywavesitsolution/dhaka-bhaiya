<?php

namespace App\Models\Product\Variant;

use App\Models\Deals\DealTable;
use App\Models\User;
use App\Models\Product\Product;
use Illuminate\Database\Eloquent\Model;
use App\Models\Product\ProductOption\ProductSize;
use App\Models\Product\MeasuringUnit\MeasuringUnit;
use App\Models\Sales\ProductHold;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class ProductVariant extends Model implements HasMedia
{
    use HasFactory;
    use InteractsWithMedia;
    use SoftDeletes;

    public static function boot(): void
    {
        parent::boot();
        static::creating(function ($model) {
            $slug = Str::slug($model->name ?? $model->product_variant_name);
            $count = static::whereRaw("slug RLIKE '^{$slug}(-[0-9]+)?$'")->count();
            $model->slug = $count ? "{$slug}-{$count}" : $slug;
        });
    }

    protected static function booted()
    {
        static::deleting(function ($variant) {
            $variant->stock()->delete();
            $variant->rates()->delete();
        });

        static::restoring(function ($variant) {
            $variant->stock()->withTrashed()->restore();
            $variant->rates()->withTrashed()->restore();
        });
    }

    protected $fillable = [
        'product_variant_name',
        'product_variant_urdu_name',
        'slug',
        'code',
        'product_id',
        'size_id',
        'description',
        'SKU',
        'measuring_unit_id',
        'min_order_qty',
        'finish_goods',
        'service_item',
        'raw_material',
        'manage_deal_items',
        'user_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }

    public function size()
    {
        return $this->belongsTo(ProductSize::class, 'size_id', 'id');
    }

    public function productVariantLocation()
    {
        return $this->hasMany(ProductVariantLocation::class, 'product_variant_id');
    }

    public function measuringUnit()
    {
        return $this->belongsTo(MeasuringUnit::class, 'measuring_unit_id', 'id');
    }

    public function rates()
    {
        return $this->hasOne(ProductVariantRate::class, 'product_variant_id');
    }

    public function stock()
    {
        return $this->hasOne(ProductVariantStock::class, 'product_variant_id');
    }

    public function ledger()
    {
        return $this->hasMany(ProductVariantLedger::class, 'product_variant_id');
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('pro_variant_image')->singleFile();
    }

    public function ProductName()
    {
        return $this->hasMany(ProductHold::class, 'product_id');
    }

    public function deal()
    {
        return $this->hasOne(DealTable::class, 'product_variant_deal_id');
    }
}
