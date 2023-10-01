<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @method static count()
 * @method static thisYear()
 * @method static where(string $string, bool $true)
 * @method static find($end_by)
 * @property string user_id
 * @property string for
 * @property string type
 * @property string name
 * @property string message
 * @property string created_by
 * @property bool mark_read
 * @property bool mark_open
 */
class Notifications extends Model
{
    protected $fillable = [
        'user_id',
        'for',
        'type',
        'name',
        'message',
        'mark_read',
        'created_by',
        'mark_open'
    ];

    /**
     * Bootstrap any application services.
     */
    public static function boot()
    {
        parent::boot();

        // Create uid when creating list.
        static::creating(function ($item) {
            // Create new uid
            $uid = uniqid();
            while (self::where('uid', $uid)->count() > 0) {
                $uid = uniqid();
            }
            $item->uid = $uid;
        });
    }

    /**
     * get user
     *
     * @return BelongsTo
     *
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * get route key by uid
     *
     * @return string
     */
    public function getRouteKeyName(): string
    {
        return 'uid';
    }


    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->name;
    }
}
