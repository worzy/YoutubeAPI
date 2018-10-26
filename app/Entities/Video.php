<?php

namespace App\Entities;

use Illuminate\Database\Eloquent\Model;

class Video extends Model
{
    // Disable timestamps
    public $timestamps = false;

    protected $fillable = ['title', 'date'];
}
