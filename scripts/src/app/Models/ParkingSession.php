<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class ParkingSession extends Model
{
    use HasFactory;
    use Notifiable;

    protected $fillable = ['parking_spot_id', 'vehicle_id', 'email', 'start_time', 'end_time'];


    public function parkingSpot()
    {
        return $this->belongsTo(ParkingSpot::class);
    }

    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class);
    }

}
