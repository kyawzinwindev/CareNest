<?php

namespace App;

enum PaymentType: string
{
    case Online = "online";
    case Onsite = "onsite";
}
