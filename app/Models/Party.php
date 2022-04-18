<?php

namespace App\Models;

use App\Traits\Uuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Party extends Model
{
    use Uuids, HasFactory;

    protected $fillable = [
        'name',
        'game_id',
        'user_id',
    ];

    protected $hidden = [
        'id',
        'created_at',
        'updated_at',
    ];
    
    /**
     * Each party can hold many users.
     *
     */
    public function users()
    {
        return $this->belongsToMany('App\User');
    }
}
