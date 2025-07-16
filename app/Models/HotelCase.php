<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class HotelCase extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'hotel_id', //ホテルID
        'hotel_name', //ホテル名
        'writer_name', //記入者名
        'guest_name', //ゲスト名（代表者名）
        'guest_count', //ゲスト人数
        'request_detail', //ご依頼内容（サービスの内容）
        'dispatch_location', //ガイドを派遣する場所
        'service_start', //サービス手配日時
        'service_end', //サービス終了日時
        'service_hours', //サービス提供時間
        'guide_language', //ガイド言語
        'vehicle_type', //希望車種
        'desired_areas', //観光エリア、スポット、アクティビティ
        'guide_report_id', //削除日
    ];

    /**
     * Hotel（ホテル）とのリレーション
     */
    public function hotel()
    {
        return $this->belongsTo(Hotel::class, 'hotel_id');
    }

    /**
     * ガイド報告書とのリレーション
     */
    public function guideReport()
    {
        return $this->belongsTo(GuideReport::class, 'guide_report_id');
    }

    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }

    public function programs()
    {
        return $this->hasMany(HotelCaseProgram::class);
    }
}
