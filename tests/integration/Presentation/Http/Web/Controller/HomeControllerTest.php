<?php

declare(strict_types=1);

namespace Micro\User\Tests\Integration\Presentation\Http\Web\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class HomeControllerTest extends WebTestCase
{
    /**
     * @test
     *
     * @group e2e
     */
    public function homeShouldHaveLinkToSignUpTest(): void
    {
        $client = self::createClient();
        $crawler = $client->request('GET', '/');

        $this->assertGreaterThan(0, $crawler->filter('html:contains("Hello!")')->count());
        $this->assertGreaterThan(0, $crawler->filter('html:contains("Sign up")')->count());
    }

    /**
     * @test
     *
     * @group e2e
     */
    public function signUpButtonShouldRedirectToSignUpPageTest(): void
    {
        $client = self::createClient();
        $crawler = $client->request('GET', '/');
        $link = $crawler->selectLink('Sign up')->link();
        $crawler = $client->click($link);

        $this->assertGreaterThan('/sign-up', $crawler->getUri());
    }
}
