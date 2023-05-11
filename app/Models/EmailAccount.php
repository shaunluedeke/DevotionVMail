<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmailAccount extends Model
{
    protected $table = 'characters_email_account';
    protected $fillable = [
        'charId',
        'address',
        'password',
        'loginChars',
        'isActive',
    ];

    protected $casts = [
        'loginChars' => 'array',
        'isActive' => 'boolean',
    ];

    protected $hidden = [
        'password',
    ];
}
