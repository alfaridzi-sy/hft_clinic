<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Service extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'price'];

    public function appointments()
    {
        return $this->belongsToMany(Appointment::class, 'appointment_service')
            ->withPivot('quantity')
            ->withTimestamps();
    }
}
