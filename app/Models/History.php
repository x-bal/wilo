<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class History extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function device()
    {
        return $this->belongsTo(Device::class);
    }

    public function modbus()
    {
        return $this->belongsTo(Modbus::class, 'modbus_id');
    }

    public function digital()
    {
        return $this->belongsTo(DigitalInput::class, 'digital_input_id');
    }
}
