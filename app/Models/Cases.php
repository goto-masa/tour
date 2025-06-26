<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @use HasFactory<\Database\Factories\CasesFactory>
 */
class Cases extends Model
{
    use HasFactory;
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
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
    ];
}
