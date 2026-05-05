<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Patient extends Model
{
    protected $fillable = [
        'user_id',
        'weight',
        'height',
        'dob'
    ];

    protected function cast(): array
    {
        return [
            'weight' => 'decimal:2',
            'height' => 'decimal:2',
            'dob' => 'date'
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function appointment(): HasMany
    {
        return $this->hasMany(Appointment::class);
    }
}
