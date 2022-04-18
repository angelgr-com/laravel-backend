<?php

namespace App\Models;

use App\Traits\Uuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Game extends Model
{
    use Uuids, HasFactory;

    protected $fillable = [
        'title',
        'thumbnail',
        'url',
    ];

    protected $hidden = [
        'game_id',
        'created_at',
        'updated_at',
    ];
}
