<?php

namespace Moox\Expiry\Models;

use Illuminate\Database\Eloquent\Model;

class Expiry extends Model
{
    protected $table = 'expiry';

    protected $fillable = [
        'name',
        'started_at',
        'finished_at',
        'failed',
    ];

    protected $casts = [
        'failed' => 'bool',
        'started_at' => 'datetime',
        'finished_at' => 'datetime',
    ];
}
