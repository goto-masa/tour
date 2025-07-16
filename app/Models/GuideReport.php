<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GuideReport extends Model
{
    use HasFactory;

    protected $fillable = [
        'guide_email', // メールアドレス
        'guide_name', // ガイド名
        'guest_name', // ゲスト名
        'number_of_guests', // ゲスト人数
        'service_date', // ガイド開始日
        'finish_time', // ガイド終了日
        'duration', // ガイド時間
        'schedules', // ガイドスケジュール
        'expenses', // 立替経費
        'report', // ガイドレポート
        'expend_price', // 立替経費金額(合計)
        'comment', // 備考
    ];

    protected $casts = [
        'schedules' => 'array',
        'expenses' => 'array',
        'service_date' => 'date',
        'finish_time' => 'date',
    ];
} 