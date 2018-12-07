<?php

declare(strict_types=1);

namespace Micro\User\Tests\UI\Http\Web\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\DomCrawler\Crawler;

class SignUpControllerTest extends WebTestCase
{
    /**
     * @test
     *
     * @group e2e
     */
    public function signUpPageFormFormatTest(): void
    {
        $client = self::createClient();
        $crawler = $client->request('GET', '/sign-up');

        $this->assertSame(1, $crawler->filter('label:contains("Email")')->count());
        $this->assertSame(1, $crawler->selectButton('Send')->count());
    }

    /**
     * @test
     *
     * @group e2e
     */
    public function signUpFormCreateUserSuccessTest(): void
    {
        $crawler = $this->createUser($email = 'ads@asd.asd');

        self::assertSame(1, $crawler->filter('html:contains("Hello ' . $email . '")')->count());
        self::assertSame(1, $crawler->filter('html:contains("Your id is ")')->count());
    }

    /**
     * @test
     *
     * @group e2e
     */
    public function signUpFormCreateUserInvalidEmailTest(): void
    {
        $crawler = $this->createUser('test@gmail');

        self::assertSame(1, $crawler->filter('html:contains("Not a valid email")')->count());
    }

    /**
     * @test
     *
     * @group e2e
     */
    public function signUpFormCreateUserWithEmailAlreadyTakenTest(): void
    {
        $this->createUser('test@gmail.com');
        $crawler = $this->createUser('test@gmail.com');

        self::assertSame(1, $crawler->filter('html:contains("Email already exists.")')->count());
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
