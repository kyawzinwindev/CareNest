<?php

namespace App\Models;

use App\Enums\Specialization;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Service extends Model
{
    protected $fillable = [
        'name',
        'description',
        'price',
        'required_prepayment',
        'specialization'
    ];

    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
            'required_prepayment' => 'bool',
            'specialization' => Specialization::class
        ];
    }

    public function doctors(): BelongsToMany
    {
        return $this->belongsToMany(Doctor::class);
    }

    public function appointments(): HasMany
    {
        return $this->hasMany(Appointment::class);
    }
}

