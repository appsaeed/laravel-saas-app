<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;


/**
 * @method static count()
 * @method static create(array $array)
 * @method static find($end_by)
 * @property string user_id
 * @property string todo_id
 * @property \App\Models\Todos todo
 * @property \App\Models\User user
 */
class TodosReceived extends Model
{
    use HasFactory;

    /**
     * Table name
     * @var string table
     */
    protected $table = 'todos_received';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        "user_id",
        "todo_id",
        'accepted'
    ];

    /**
     * @var array
     */
    public static $roles = [
        "user_id" => 'required',
        "todo_id" => 'required',
    ];

    /**
     * Todo status list
     * @var array
     */
    public static $status = [
        'available', 'in_progress', 'review', 'complete',  'pending', 'pause', 'continue'
    ];

    /**
     * Find item by uid.
     *
     * @param $uid
     *
     * @return object
     */
    public static function findByUid($uid): object
    {
        return self::where('uid', $uid)->first();
    }

    public function systemJobs(): HasMany
    {
        return $this->hasMany(SystemJob::class)->orderBy('created_at', 'desc');
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
     * get Todos
     * 
     * @return \App\Models\Todos
     * 
     */
    public function todo(): BelongsTo
    {
        return $this->belongsTo(Todos::class, 'todo_id');
    }
}
