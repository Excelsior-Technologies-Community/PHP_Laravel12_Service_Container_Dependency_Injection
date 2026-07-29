<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GreetingHistory extends Model
{
    protected $fillable = [

        'greeting_type',

        'theme',

        'message',

    ];
}
