<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CRMDashboard extends Model
{
    use HasFactory;

    //use HasFactory;
    public $timestamps = true;

    protected $table = 'crm_dashboard';

    protected $fillable = [
        'sid',
        'callsid',
        'user_id',
        'voicebox',
        'address',
        'description',
        'agent',
        'saved',
        'message1',
        'message2',
        'message3',
        'called',
        'from',
        'number',
        'property',
        'notes',
        'disposition',
        'more',
    ];

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

    /**
     * get all plans
     *
     * @return mixed
     */

    public static function getAll()
    {
        return self::select('*');
    }
}
