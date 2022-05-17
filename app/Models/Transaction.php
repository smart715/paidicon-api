<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Transaction extends Model
{
    use HasFactory;
    protected $fillable = [
        'uuid',
        'amount',
        'stripe_id',
        'user_id',
        'type',
        'order_id',
        'referrer_id',
        'status',
        'refund_id',
        'updated_by_user_id',
        'bank_account',
        'card'

    ];


    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }


    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }



    public function referrer(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    protected $hidden = [];

    protected $casts = [];
}
