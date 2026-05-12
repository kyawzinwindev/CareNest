<?php

namespace App\Models;

use App\Enums\TimeSlotStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class TimeSlot extends Model
{
    protected $fillable = [
        'schedule_id',
        'start_time',
        'end_time',
        'status'
    ];

    protected function casts(): array
    {
        return [
            'status' => TimeSlotStatus::class
        ];
    }

    public function schedule(): BelongsTo
    {
        return $this->belongsTo(Schedule::class);
    }

    public function appointment(): HasOne
    {
        return $this->hasOne(Appointment::class);
    }
}
