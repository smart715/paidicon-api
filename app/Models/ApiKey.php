<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ApiKey extends Model
{
    use HasFactory;

    protected $fillable = [
        'uuid',
        'package_id',
        'package_features',
        'key',
        'restrict_to_ip_address',
        'user_id',
        'ip_requests_limit',
        'ip_requests_limit_seconds',
        'ip_requests_interval_after_limit_seconds',
        'expires',
        'updated_by_user_id'

    ];

    protected $hidden = [];

    protected $casts = [];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
