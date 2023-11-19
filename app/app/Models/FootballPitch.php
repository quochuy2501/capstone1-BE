<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FootballPitch extends Model
{
    use HasFactory;

    protected $table = 'football_pitches';

    protected $fillable = [
        'name',
        'describe',
        'image',
        'price',
        'detailed_schedule',
        'id_owner',
        'id_category',
    ];
}
