<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Modbus extends Model
{
    use HasFactory;
    protected $guarded = [];

    function device()
    {
        return $this->belongsTo(Device::class);
    }
}