<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class HotelCase extends Model
{
    use SoftDeletes;

    protected $table = 'cases';

    protected $fillable = [
        'hotel_name',
        'writer_name',
        'guest_name',
        'guest_count',
        'request_detail',
        'dispatch_location',
        'service_start',
        'service_end',
        'service_hours',
        'guide_language',
        'vehicle_type',
        'desired_areas',
        'extra_requests',
        'multi_day',
        'day2_start',
        'day2_end',
        'day3_start',
        'day3_end',
        'others_schedule',
        'guide_report_id',
    ];

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
}
