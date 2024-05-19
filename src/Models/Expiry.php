<?php

namespace Moox\Expiry\Models;

use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Moox\Press\QueryBuilder\UserQueryBuilder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

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
        'expiry_job',
    ];

    protected $searchableFields = ['*'];

    protected $casts = [
        'expired_at' => 'datetime',
        'notified_at' => 'datetime',
        'escalated_at' => 'datetime',
        'done_at' => 'datetime',
    ];

    /**
     * Get the owning user model for notify.
     */
    public function notifyUser(): BelongsTo
    {
        return $this->belongsTo(config('expiry.user_model'), 'notified_to', 'ID');
    }

    /**
     * Get the owning user model for escalate.
     */
    public function escalateUser(): BelongsTo
    {
        return $this->belongsTo(config('expiry.user_model'), 'escalated_to', 'ID');
    }

    /**
     * Use the custom query builder for the model.
     */
    protected function newBaseQueryBuilder()
    {
        $connection = $this->getConnection();

        return new UserQueryBuilder(
            $connection, $connection->getQueryGrammar(), $connection->getPostProcessor()
        );
    }

    public static function getExpiryJobOptions(): Collection
    {
        return static::select('expiry_job')
            ->distinct()
            ->pluck('expiry_job', 'expiry_job');
    }

    public static function getUserOptions(): array
    {
        $notifiedToUserIds = Expiry::pluck('notified_to')->unique();
        $users = config('expiry.user_model')::whereIn('id', $notifiedToUserIds)->pluck('displayname', 'id')->toArray();
        return $users;
    }
}
