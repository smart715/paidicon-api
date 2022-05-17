<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmailTemplate extends Model
{
    use HasFactory;
    /** @var string[]  */
    protected $fillable = [
        'uuid',
        'name',
        'subject',
        'header',
        'signature',
        'body',
        'footer',
        'updated_by_user_id'
    ];

    protected $hidden = [];

    protected $casts = [];
}
