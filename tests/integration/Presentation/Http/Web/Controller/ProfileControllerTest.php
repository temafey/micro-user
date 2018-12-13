<?php

declare(strict_types=1);

namespace Micro\User\Tests\Integration\Presentation\Http\Web\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;

class ProfileControllerTest extends WebTestCase
{
    /**
     * @test
     *
     * @group e2e
     */
    public function anonUserShouldBeRedirectedToSignInTest(): void
    {
        $client = self::createClient();
        $client->request('GET', '/profile');

        /** @var RedirectResponse $response */
        $response = $client->getResponse();
        $this->assertSame(Response::HTTP_FOUND, $response->getStatusCode());
        $this->assertContains('/sign-in', $response->getTargetUrl());
    }
}
