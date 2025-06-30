<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GuideReport extends Model
{
    use HasFactory;

    protected $table = 'guide_report';

    protected $fillable = [
        'guide_email',
        'guide_name',
        'guest_name',
        'number_of_guests',
        'service_date',
        'finish_time',
        'duration',
        'schedules',
        'expenses',
        'report',
        'comment',
    ];

    protected $casts = [
        'schedules' => 'array',
        'expenses' => 'array',
        'service_date' => 'date',
        'finish_time' => 'date',
    ];
} 