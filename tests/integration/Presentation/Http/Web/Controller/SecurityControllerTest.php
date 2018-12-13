<?php

declare(strict_types=1);

namespace Micro\User\Tests\Integration\Presentation\Http\Web\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\DomCrawler\Crawler;

class SecurityControllerTest extends WebTestCase
{
    /**
     * @test
     *
     * @group e2e
     */
    public function signInAfterCreateUserTest(): void
    {
        $this->createUser('test@gmail.com');
        $client = self::createClient();
        $crawler = $client->request('GET', '/sign-in');

        $form = $crawler->selectButton('Sign in')->form();
        $form->get('_email')->setValue('test@gmail.com');
        $form->get('_password')->setValue('crqs-demo');

        $client->submit($form);
        $crawler = $client->followRedirect();

        self::assertSame('/profile', parse_url($crawler->getUri(), PHP_URL_PATH));
        self::assertSame(1, $crawler->filter('html:contains("Hi test@gmail.com")')->count());
    }

    /**
     * @test
     *
     * @group e2e
     */
    public function logoutShouldRemoveSessionAndProfileRedirectSignInTest(): void
    {
        $this->createUser('test@gmail.com');
        $client = self::createClient();
        $crawler = $client->request('GET', '/sign-in');

        $form = $crawler->selectButton('Sign in')->form();
        $form->get('_email')->setValue('test@gmail.com');
        $form->get('_password')->setValue('crqs-demo');

        $client->submit($form);
        $crawler = $client->followRedirect();
        self::assertSame('/profile', parse_url($crawler->getUri(), PHP_URL_PATH));

        $client->click($crawler->selectLink('Exit')->link());
        $crawler = $client->followRedirect();
        self::assertSame('/', parse_url($crawler->getUri(), PHP_URL_PATH));

        $client->request('GET', '/profile');
        $crawler = $client->followRedirect();
        self::assertSame('/sign-in', parse_url($crawler->getUri(), PHP_URL_PATH));
    }

    /**
     * @test
     *
     * @group e2e
     */
    public function loginShouldDisplayAnErrorWhenBadCredentialsTest(): void
    {
        $client = self::createClient();
        $crawler = $client->request('GET', '/sign-in');

        $form = $crawler->selectButton('Sign in')->form();
        $form->get('_email')->setValue('an@email.com');
        $form->get('_password')->setValue('password-so-safe');
        $client->submit($form);
        $crawler = $client->followRedirect();

        self::assertSame(1, $crawler->filter('html:contains("An authentication exception occurred.")')->count());
    }

    private function createUser(string $email, string $password = 'crqs-demo'): Crawler
    {
        $client = self::createClient();
        $crawler = $client->request('GET', '/sign-up');

        $form = $crawler->selectButton('Send')->form();
        $form->get('email')->setValue($email);
        $form->get('password')->setValue($password);

        $crawler = $client->submit($form);

        return $crawler;
    }
}
