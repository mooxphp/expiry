<?php

namespace Moox\Expiry\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Expiry extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'title',
        'slug',
        'item_id',
        'meta_id',
        'link',
        'expired_at',
        'notified_at',
        'notified_to',
        'escalated_at',
        'escalated_to',
        'handled_by',
        'done_at',
        'expiry_monitor_id',
    ];

    protected $searchableFields = ['*'];

    protected $casts = [
        'expired_at' => 'datetime',
        'notified_at' => 'datetime',
        'escalated_at' => 'datetime',
        'done_at' => 'datetime',
    ];

    public function expiryMonitor()
    {
        return $this->belongsTo(ExpiryMonitor::class);
    }
}
