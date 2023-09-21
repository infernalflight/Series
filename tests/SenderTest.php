<?php

namespace App\Tests;

use App\Entity\User;
use App\Helper\Sender;
use PHPUnit\Framework\TestCase;

class SenderTest extends TestCase
{
    public function testSendNotification(): void
    {
        $user = new User();
        $mail = "test@gmail.com";
        $user->setEmail($mail);

        $sender = new Sender();

        $sender->sendNewUserNotificationToAdmin($user);

        $this->assertContains(
            file_get_contents('public/notif.txt'),
            [$mail],
            "Le Fichier ne contient pas le mail de l'utilisateur"
        );
    }
}
