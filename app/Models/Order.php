<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;
    //public $timestamps = false;
    protected $fillable = [
        'uuid', 'user_id', 'package_id', 'payment_method', 'transaction_id', 'amount',
        'discount', 'discount_reason', 'total_payable', 'package_status', 'updated_by_user_id',
        'status'
    ];
    protected $hidden = [
    ];

    protected $casts = [
        'uuid' => 'string'
    ];
}
