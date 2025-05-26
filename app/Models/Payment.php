<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = ['appointment_id', 'total', 'method', 'paid_at'];

    public function appointment()
    {
        return $this->belongsTo(Appointment::class);
    }
}
