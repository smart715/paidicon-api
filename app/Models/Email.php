<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Email extends Model
{
    use HasFactory;
    protected $table="email_templates";
    protected $fillable = [
        'uuid',
        'name',
        'subject',
        'content',
        'body',
        'updated_by_user_id'

    ];

    protected $hidden = [];

    protected $casts = [];
}
