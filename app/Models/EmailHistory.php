<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmailHistory extends Model
{
    /** @var string[]  */
    protected $fillable = [
        'uuid',
        'name',
        'subject',
        'signature',
        'body',
        'email_template_id',
        'sent_by_user'
    ];
}
