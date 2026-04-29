<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{

    protected $fillable = [
        'event_start_date',
        'event_start_time',
        'event_end_date',
        'event_end_time',
        'event_title',
        'event_description',
    ];}
