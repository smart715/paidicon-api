<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdminSetting extends Model
{
    use HasFactory;
    protected $fillable = [
        'uuid',
        'site_name',
        'timezone',
        'locale',
        'maintenance_mode',
        'maintenance_start',
        'maintenance_end',
        'maintenance_reason',
        'p_requests_limit',
        'ip_requests_limit_seconds',
        'ip_requests_interval_after_limit_seconds',
        'referral_percentage',
        'minimimum_referral_payout_amount',
        'discount_percentage_on_first_payment',
        'branding_text',
        'updated_by_user_id'

    ];

    protected $hidden = [];

    protected $casts = [];
}
