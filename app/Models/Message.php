<?php

namespace App\Models;

use App\Traits\Uuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    use Uuids, HasFactory;

    protected $fillable = [
        'from',
        'message',
        'date',
        'party_id',
    ];
}
