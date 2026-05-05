<?php

namespace App;

enum Role: string
{
    case Root = 'root';
    case Admin = 'admin';
    case Doctor = 'doctor';
    case Patient = 'patient';
}
