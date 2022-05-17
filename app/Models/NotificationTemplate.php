<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NotificationTemplate extends Model
{

    protected $fillable = [
        'uuid',
        'name',
        'title',
        'content',
        'updated_by_user_id'
    ];

    protected $hidden = [];

    protected $casts = [];

}
