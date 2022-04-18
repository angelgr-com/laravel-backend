<?php

namespace App\Models;

use App\Traits\Uuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Party_User extends Model
{
    use Uuids, HasFactory;

    protected $fillable = [
        'party_id',
        'user_id',
    ];
}
