<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @method static where(string $string, mixed $id)
 */
class ChatBoxMessage extends Model
{
    protected $fillable = [
        'box_id',
        'message',
        'media_url',
        'sms_type',
        'send_by',
        'sending_server_id',
    ];
}
