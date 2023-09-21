<?php

namespace App\Tests;

use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class MainTest extends WebTestCase
{

    const USER = [
        'username' => 'tom',
        'email' => 'tom@gmail.com',
        'password' => '123456',
    ];

    public function testHomePageIsWorking(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', 'Bienvenue sur Series.com');
    }

    public function testListSerieIsNotWorkingIfUserNotLogged(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/series/list');

        $this->assertResponseRedirects('/login', 302);
    }

    public function testListSerieIsWorkingIfUserIeLogged(): void
    {
        $client = static::createClient();
        $userRepository = static::getContainer()->get(UserRepository::class);
        $user = $userRepository->loadUserByIdentifier('milou@gmail.com');

        $client->loginUser($user);

        $crawler = $client->request('GET', '/series/list');

        $this->assertResponseIsSuccessful();
    }

    public function testAccountCreation(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/register');

        $client->submitForm('Register', [
            'registration_form[username]' => self::USER['username'],
            'registration_form[email]' => self::USER['email'],
            'registration_form[plainPassword][first]' => self::USER['password'],
            'registration_form[plainPassword][second]' => self::USER['password'],
            'registration_form[agreeTerms]' => 1,
        ]);

        $this->assertResponseRedirects('/series/list', 302, 'Not working');

        // On nettoie la base de test pour pouvoir reproduire le scénario
        $userRepository = static::getContainer()->get(UserRepository::class);
        $user = $userRepository->findOneBy(['email' => self::USER['email']]);

        if ($user) {
            $em = static::getContainer()->get(EntityManagerInterface::class);
            $em->remove($user);
            $em->flush();
        }
    }

}
