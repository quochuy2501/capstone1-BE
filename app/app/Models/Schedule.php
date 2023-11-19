<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    use HasFactory;
    protected $table = 'schedules';

    protected $fillable = [
        'pitch_id',
        'user_id',
        'date',
        'time_start',
        'time_end',
        'payment_id',
        'total_hour',
        'total_price',
    ];
}
