<?php

namespace App;

enum PaymentMethod: string
{
    case Card = 'card';
    case QR = 'qr';
}
