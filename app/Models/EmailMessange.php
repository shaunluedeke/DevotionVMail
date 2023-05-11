<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmailMessange extends Model
{
    protected $table = 'characters_email_messanges';
    protected $fillable = [
        'accountId',
        'reciever',
        'sender',
        'replyTo',
        'subject',
        'text',
        'folder',
        'recieved',
        'status',
    ];
}
