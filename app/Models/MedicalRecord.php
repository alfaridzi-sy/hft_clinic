<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MedicalRecord extends Model
{
    use HasFactory;

    protected $fillable = ['appointment_id', 'subjective', 'objective', 'assessment', 'plan'];

    public function appointment()
    {
        return $this->belongsTo(Appointment::class);
    }
}
