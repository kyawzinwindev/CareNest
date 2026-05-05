<?php

namespace App\Enums;

enum PaymentMethod: string
{
    case CARD = 'card';
    case QR = 'qr';
}

