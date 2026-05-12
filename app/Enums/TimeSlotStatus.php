<?php

namespace App\Enums;

enum TimeSlotStatus: string
{
    case AVAILABLE = 'available';
    case UNAVAILABLE = 'unavailable';
}
