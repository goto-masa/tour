<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @use HasFactory<\Database\Factories\PriceFactory>
 */
class Price extends Model
{
    use HasFactory;
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'service',
        'type',
        'duration',
        'price_including_tax',
        'price_excluding_tax',
    ];

    /**
     * 税抜価格を取得（アクセサ）
     */
    public function getPriceExcludingTaxAttribute()
    {
        if ($this->price_including_tax !== null) {
            return round($this->price_including_tax / 1.1);
        }
        return null;
    }
}
