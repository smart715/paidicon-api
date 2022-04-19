<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;
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
