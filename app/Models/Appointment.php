<?php

namespace App\Models;

use App\AppointmentStatus;
use App\PaymentType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Appointment extends Model
{
    protected $fillable = [
        'patient_id',
        'doctor_id',
        'schedule_id',
        'service_id',
        'start_time',
        'end_time',
        'payment_type',
        'status'
    ];

    protected function cast(): array
    {
        return [
            'start_time' => 'datetime',
            'end_time' => 'datetime',
            'payment_type' => PaymentType::class,
            'status' => AppointmentStatus::class
        ];
    }

    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }

    public function doctor(): BelongsTo
    {
        return $this->belongsTo(Doctor::class);
    }

    public function schedule(): BelongsTo
    {
        return $this->belongsTo(Schedule::class);
    }

    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class);
    }

    public function payment(): HasOne
    {
        return $this->hasOne(Payment::class);
    }
}
