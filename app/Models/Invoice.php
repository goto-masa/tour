<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    protected $fillable = ['hotel_case_id', 'file_path'];

    public function hotelCase()
    {
        return $this->belongsTo(HotelCase::class);
    }
}
