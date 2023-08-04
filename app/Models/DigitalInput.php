<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DigitalInput extends Model
{
    use HasFactory;
    protected $guarded = [];

    function device()
    {
        return $this->belongsTo(Device::class);
    }

    function histories()
    {
        return $this->hasMany(History::class);
    }
}
