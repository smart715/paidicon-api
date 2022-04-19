<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClientPluginSetting extends Model
{
    use HasFactory;
    protected $table="client_plugin_settings_backup";
    protected $fillable = [
        'uuid',
        'user_id',
        'api_id',
        'settings',
        'updated_by_user_id'

    ];

    protected $hidden = [];

    protected $casts = [];
}
