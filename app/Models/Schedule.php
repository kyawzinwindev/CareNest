<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Schedule extends Model
{
    protected $fillable = [
        'doctor_id',
        'date',
        'start_time',
        'end_time',
        'slot_duration_minutes'
    ];

    protected function cast(): array
    {
        return [
            'date' => 'date',
            'slot_duration_minutes' => 'integer'
        ];
    }

    public function doctor(): BelongsTo
    {
        return $this->belongsTo(Doctor::class);
    }

    public function time_slot(): HasMany
    {
        return $this->hasMany(TimeSlot::class);
    }
}
