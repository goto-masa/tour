<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HotelCaseTravelProgram extends Model
{
    // fillableプロパティを追加
    protected $fillable = [
        'case_id',             // 案件ID
        'name',                // プログラム名
        'guest_name',          // ゲスト名
        'price',               // 税込金額
        'price_without_tax',   // 税抜価格
    ];

    /**
     * HotelCase（案件）とのリレーション
     */
    public function case()
    {
        return $this->belongsTo(HotelCase::class, 'hotel_case_id');
    }
}
