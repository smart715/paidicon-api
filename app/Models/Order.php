<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    use HasFactory;

    //public $timestamps = false;
    protected $fillable = [
        'uuid',
        'user_id',
        'package_id',
        'payment_method',
        'amount',
        'discount',
        'discount_reason',
        'total_payable',
        'package_status',
        'updated_by_user_id',
        'status'
    ];
    protected $hidden = [
    ];

    protected $casts = [
        'uuid' => 'string'
    ];

    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class);
    }

    public function package(): BelongsTo
    {
        return $this->belongsTo(Package::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
