<?php

namespace App\Enums;

enum ForecastStatus: string
{
    case Active = 'active';       // Still in funnel
    case Converted = 'converted'; // Won and turned into DemandRequests
    case Archived = 'archived';   // Lost or expired
}