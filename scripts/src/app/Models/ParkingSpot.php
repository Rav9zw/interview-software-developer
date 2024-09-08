<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;


class ParkingSpot extends Model
{
    use HasFactory;

    protected $fillable = ['spot_number', 'spot_size', 'floor'];

    public function parkingSession(): HasMany
    {
        return $this->hasMany(ParkingSession::class);
    }
}
