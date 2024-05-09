<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @method truncate()
 * @method static thisYear()
 * @method public static where(string $string, bool $true)
 * @method getProvider($provider)
 * @method providers()
 * @method truncate()
 * @method public status create(array $array)
 * @method assigned()
 * @method addEmployee()
 * @method hasEmployee()
 * @method addRevew()
 * @method setLastCron()
 * @method hasRevew()
 * @method isCreator()
 * @method getReviewers()
 * @property string id
 * @property string uid
 * @property string user_id
 * @property string last_cron
 * @property string name
 * @property string title
 * @property string description
 * @property array status
 * @property mixed assign_to
 * @property mixed options
 * @property mixed last_updated_by
 * @property string deadline
 * @property mixed employees
 * @property mixed reviewers
 * @property \App\Models\User user
 */
class Project extends Model {
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        "user_id",
        'assign_to',
        'employees',
        'update_message',
        'status',
        'last_cron',
        'last_updated_by',
        'completed_by',
        'reviewers',
        'options',
        'name',
        'title',
        'description',
        'deadline',
        'note',
    ];

    /**
     * @var array
     */
    public static $roles = [
        'assign_to' => 'required',
        'status' => 'required',
        'name' => 'required',
    ];

    /**
     * Todo status list
     * @var array
     */
    public static $status_all = [
        'available',
        'in_progress',
        'review',
        'complete',
        'pause',
        'continue',
    ];
    /**
     * Todo status list
     * @var array
     */
    public static $status = [
        // 'available',
        'in_progress',
        'review',
        // 'complete',
        'pause',
        'continue',
    ];

    /**
     * @var array
     */
    public static $updatefillable = ['status', 'note'];

    /**
     * Find item by uid.
     *
     * @param $uid
     *
     * @return object
     */
    public static function findByUid( $uid ): object {
        return self::where( 'uid', $uid )->first();
    }

    /**
     * get route key by uid
     *
     * @return string
     */
    public function getRouteKeyName(): string {
        return 'uid';
    }

    /**
     * Bootstrap any application services.
     */
    public static function boot() {
        parent::boot();

        // Create uid when creating list.
        static::creating( function ( $item ) {
            // Create new uid
            $uid = uniqid();
            while ( self::where( 'uid', $uid )->count() > 0 ) {
                $uid = uniqid();
            }
            $item->uid = $uid;
        } );
    }

    /**
     * get user
     *
     * @return \App\Models\User
     */
    public function user(): BelongsTo {
        return $this->belongsTo( User::class );
    }
    /**
     * completed_by
     * @return \App\Models\User
     */
    public function completed_by(): BelongsTo {
        return $this->belongsTo( User::class, 'completed_by' );
    }

    /**
     * @return mixed
     */
    public function assigned(): mixed {
        try {
            return json_decode( $this->assign_to );
        } catch ( \Throwable $th ) {
            return [];
        }
    }

    /**
     * isCreator
     * @return bool
     */
    public function isCreator() {
        return auth()->user()->id === $this->user_id;
    }

    /**
     * add employee
     * @param string|int $employee
     * @return bool
     */
    public function addEmployee(): bool {
        try {

            $employees = $this->employees ? json_decode( $this->employees ) : [];

            if ( empty( $employees ) ) {
                $employees = [];
            }

            if ( !in_array( auth()->user()->id, $employees ) ) {

                if ( $this->status === 'available' ) {
                    $this->update( [
                        'status' => 'in_progress',
                    ] );
                }

                $employees[] = auth()->user()->id;

                return $this->update( ['employees' => json_encode( $employees )] );
            }
            return false;
        } catch ( \Throwable $th ) {
            return false;
        }
    }
    /**
     * add hasEmployee
     * @param string|int $employee
     * @return bool
     */
    public function hasEmployee(): bool {
        try {
            $employees = $this->employees ? json_decode( $this->employees ) : [];
            $employees = empty( $employees ) ? [] : $employees;
            return in_array( auth()->user()->id, $employees );
        } catch ( \Throwable $th ) {
            return false;
        }
    }
    /**
     * add hasEmployee
     * @param string|int $employee
     * @return bool
     */
    public function addReview(): bool {
        try {
            $reviews = $this->reviewers ? json_decode( $this->reviewers ) : [];

            if ( empty( $reviews ) ) {
                $reviews = [];
            }

            $reviews[] = auth()->user()->id;

            if ( $this->status === 'in_progress' ) {
                $this->update( [
                    'status' => 'review',
                ] );
            }

            return $this->update( ['reviewers' => json_encode( $reviews )] );
        } catch ( \Throwable $th ) {
            return false;
        }
    }
    /**
     * add hasEmployee
     * @param string|int $employee
     * @return bool
     */
    public function hasReview(): bool {
        try {
            $user_id = auth()->user()->id;
            $reviews = $this->reviewers ? json_decode( $this->reviewers ) : [];
            $reviews = empty( $reviews ) ? [] : $reviews;
            return in_array( $user_id, $reviews );
        } catch ( \Throwable $th ) {
            return false;
        }
    }
    /**
     * @method getReviewers
     * @param string|int $employee
     * @return mixed
     */
    public function getReviewers() {
        try {
            $users = [];
            $reviews = $this->reviewers ? json_decode( $this->reviewers ) : [];
            foreach ( $reviews as $user_id ) {
                if ( User::find( $user_id ) ) {
                    $users[] = User::find( $user_id );
                }
            }
            return $users;
        } catch ( \Throwable $th ) {
            return [];
        }
    }

    /**
     * @method lastUpdateBy()
     * @return \App\Models\User
     */
    public function lastUpdateBy(): BelongsTo {
        return $this->belongsTo( User::class, 'last_updated_by' );
    }

    /**
     * get options
     * @return mixed
     */
    public function getOptions() {
        try {
            return json_decode( $this->options, true );
        } catch ( \Throwable $th ) {
            return [];
        }
    }

    /**
     * @param string $name
     */
    public function getOption( string $name ) {
        if ( gettype( $this->getOptions() ) == 'array' && isset( $this->getOptions()[$name] ) ) {
            return $this->getOptions()[$name];
        }

        return null;
    }

    /**
     * @method setOption
     * @param string $key
     * @param string $value
     */
    public function setOption( string $key, $value ) {
        try {

            if ( gettype( $this->getOptions() ) === 'array' ) {
                $data = [
                     ...$this->getOptions(),
                    $key => $value,
                ];
                $this->options = $data;
                $this->save();
            } else {
                $data = [
                    $key => $value,
                ];
                $this->options = $data;
                $this->save();
            }

            return $data;
        } catch ( \Throwable $th ) {
            return $th->getMessage();
        }
    }
    /**
     * @method removeOption
     * @param string $name
     */
    public function removeOption( string $name ) {
        try {

            if ( gettype( $this->getOptions() ) === 'array' ) {

                $data = $this->getOptions();
                unset( $data[$name] );
                $this->update( ['options' => json_encode( $data )] );
            }

            return $data;
        } catch ( \Throwable $th ) {
            return $th->getMessage();
        }
    }

    /**
     * set last cron
     * @param string $value
     */
    public function setLastCron( string $value ) {
        $this->last_cron = $value;
        $this->save();
    }
}
