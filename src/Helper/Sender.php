<?php

namespace App\Helper;

use App\Entity\User;

class Sender
{
    public function sendNewUserNotificationToAdmin(User $user): void
    {
        file_put_contents('public/notif.txt', $user->getEmail());
    }

}