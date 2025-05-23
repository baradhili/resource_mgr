<?php

namespace App\Listeners\Teamwork;

use Teamwork;

class JoinTeamListener
{
    /**
     * See if the session contains an invite token on login and try to accept it.
     *
     * @param  mixed  $event
     */
    public function handle($event): void
    {
        if (session('invite_token')) {
            if ($invite = Teamwork::getInviteFromAcceptToken(session('invite_token'))) {
                Teamwork::acceptInvite($invite);
            }
            session()->forget('invite_token');
        }
    }
}
