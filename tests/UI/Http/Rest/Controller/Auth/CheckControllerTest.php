<?php

declare(strict_types=1);

namespace Micro\User\Tests\UI\Http\Rest\Controller\Auth;

use Micro\User\Tests\UI\Http\Rest\Controller\JsonApiTestCase;
use Symfony\Component\HttpFoundation\Response;

class CheckControllerTest extends JsonApiTestCase
{
    /**
     * @test
     *
     * @group e2e
     */
    public function badCredentialsMustFailWith401Test(): void
    {
        $this->post('/api/auth_check', [
            '_username' => 'oze@lol.com',
            '_password' => 'qwer',
        ]);

        self::assertSame(Response::HTTP_UNAUTHORIZED, $this->client->getResponse()->getStatusCode());
    }

    /**
     * @test
     *
     * @group e2e
     */
    public function emailMustBeValidOrFailWith400Test(): void
    {
        $this->post('/api/auth_check', [
            '_username' => 'oze@',
            '_password' => 'qwer',
        ]);

        self::assertSame(Response::HTTP_BAD_REQUEST, $this->client->getResponse()->getStatusCode());
    }
}
