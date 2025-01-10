<?php

use App\Models\Team;
use App\Models\User;
use Illuminate\Support\Facades\Log;

if(!function_exists('teamHeirarchy')) {
    function teamHeirarchy(User $user) {
        Log::info('teamHeirarchy function called');
        return "hi";
    }
}