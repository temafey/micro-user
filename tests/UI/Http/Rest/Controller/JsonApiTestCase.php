<?php

declare(strict_types=1);

namespace Micro\User\Tests\UI\Http\Rest\Controller;

use League\Tactician\CommandBus;
use Micro\User\Application\Command\User\SignUp\SignUpCommand;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;
use Symfony\Bundle\FrameworkBundle\Client;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

abstract class JsonApiTestCase extends WebTestCase
{
    public const DEFAULT_EMAIL = 'lol@lo.com';

    public const DEFAULT_PASS = '1234567890';

    /** @var null|Client */
    protected $client;

    /** @var null|string */
    private $token;

    /** @var null|UuidInterface */
    protected $userUuid;

    protected function setUp()
    {
        $this->client = static::createClient();
    }

    /**
     * @throws \Assert\AssertionFailedException
     * @throws \Exception
     */
    protected function createUser(string $email = self::DEFAULT_EMAIL, string $password = self::DEFAULT_PASS): string
    {
        $this->userUuid = Uuid::uuid4();
        $signUp = new SignUpCommand(
            $this->userUuid->toString(),
            $email,
            $password
        );
        /** @var CommandBus $commandBus */
        $commandBus = $this->client->getContainer()->get('tactician.commandbus.command');
        $commandBus->handle($signUp);

        return $email;
    }

    protected function post(string $uri, array $params)
    {
        $this->client->request(
            'POST',
            $uri,
            [],
            [],
            $this->headers(),
            (string) json_encode($params)
        );
    }

    protected function get(string $uri, array $parameters = [])
    {
        $this->client->request(
            'GET',
            $uri,
            $parameters,
            [],
            $this->headers()
        );
    }

    protected function auth(string $username = self::DEFAULT_EMAIL, string $password = self::DEFAULT_PASS): void
    {
        $this->post('/api/auth_check', [
            '_username' => $username ?: self::DEFAULT_EMAIL,
            '_password' => $password ?: self::DEFAULT_PASS,
        ]);
        $response = json_decode($this->client->getResponse()->getContent(), true);
        $this->token = $response['token'];
    }

    protected function logout(): void
    {
        $this->token = null;
    }

    private function headers(): array
    {
        $headers = [
            'CONTENT_TYPE' => 'application/json',
        ];

        if ($this->token) {
            $headers['HTTP_Authorization'] = 'Bearer ' . $this->token;
        }

        return $headers;
    }

    protected function tearDown()
    {
        $this->client = null;
        $this->token = null;
        $this->userUuid = null;
    }
}
