<?php

namespace Moox\Expiry\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExpiryMonitor extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'description',
        'runs',
        'monitors',
        'executes',
        'notifies',
        'escalates',
    ];

    protected $searchableFields = ['*'];

    protected $table = 'expiry_monitors';

    public function expiries()
    {
        return $this->hasMany(Expiry::class);
    }
}
