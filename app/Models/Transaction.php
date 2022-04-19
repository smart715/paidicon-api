<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;
    protected $fillable = [
        'uuid',
        'user_id',
        'type',
        'order_id',
        'referrer_id',
        'status',
        'updated_by_user_id'

    ];

    protected $hidden = [];

    protected $casts = [];
}
