<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Device extends Model
{
    use HasFactory;
    protected $guarded = [];

    function company()
    {
        return $this->belongsTo(Company::class);
    }

    function modbuses()
    {
        return $this->hasMany(Modbus::class);
    }

    function digitalInputs()
    {
        return $this->hasMany(DigitalInput::class);
    }
}
