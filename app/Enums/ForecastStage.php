<?php

namespace App\Enums;

enum ForecastStage: string
{
    case Plan = 'plan';         // High level, no details
    case Idea = 'idea';         // Solution shaped, high-level FTEs known
    case Estimate = 'estimate'; // RM involved, detailed DemandRequest created
    case Won = 'won';           // Contract signed
    case Lost = 'lost';         // Deal lost
}