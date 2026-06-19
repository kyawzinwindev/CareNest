<?php

namespace App\Models;

use App\Enums\TimeSlotStatus;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Override;

class Schedule extends Model
{
    protected $fillable = [
        'doctor_id',
        'date',
        'start_time',
        'end_time',
        'slot_duration_minutes'
    ];

    #[Override]
    protected static function booted(): void
    {
        static::created(function ($schedule) {
            $schedule->generateTimeSlots();
        });
    }

    protected function casts(): array
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

    public function time_slots(): HasMany
    {
        return $this->hasMany(TimeSlot::class);
    }

    public function generateTimeSlots(): void
    {
        $start = Carbon::parse($this->start_time);
        $end = Carbon::parse($this->end_time);

        while ($start < $end) {
            $slotEnd = $start->copy()
                ->addMinutes($this->slot_duration_minutes);

            if ($slotEnd > $end) {
                break;
            }

            TimeSlot::create([
                'schedule_id' => $this->id,
                'start_time' => $start->format("H:i:s"),
                'end_time' => $slotEnd->format("H:i:s"),
                'status' => TimeSlotStatus::AVAILABLE
            ]);

            $start->addMinutes($this->slot_duration_minutes);
        }
    }
}
