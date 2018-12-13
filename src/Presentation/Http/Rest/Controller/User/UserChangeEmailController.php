<?php

declare(strict_types=1);

namespace Micro\User\Presentation\Http\Rest\Controller\User;

use Assert\Assertion;
use League\Tactician\CommandBus;
use Micro\User\Application\Command\User\ChangeEmail\ChangeEmailCommand;
use Micro\User\Domain\Auth\SessionInterface;
use Micro\User\Domain\Exception\ForbiddenException;
use Micro\User\Presentation\Http\Rest\Controller\CommandController;
use Nelmio\ApiDocBundle\Annotation\Security;
use Swagger\Annotations as SWG;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

final class UserChangeEmailController extends CommandController
{
    /**
     * @Route(
     *     "/users/{uuid}/email",
     *     name="user_change_email",
     *     methods={"POST"}
     * )
     *
     * @SWG\Response(
     *     response=201,
     *     description="Email changed"
     * )
     * @SWG\Response(
     *     response=400,
     *     description="Bad request"
     * )
     * @SWG\Response(
     *     response=409,
     *     description="Conflict"
     * )
     * @SWG\Parameter(
     *     name="change-email",
     *     type="object",
     *     in="body",
     *     required=true,
     *     schema=@SWG\Schema(type="object",
     *         @SWG\Property(property="email", type="string")
     *     )
     * )
     * @SWG\Parameter(
     *     name="uuid",
     *     type="string",
     *     in="path"
     * )
     *
     * @SWG\Tag(name="User")
     *
     * @Security(name="Bearer")
     *
     * @throws \Assert\AssertionFailedException
     */
    public function __invoke(string $uuid, Request $request): JsonResponse
    {
        $this->validateUuid($uuid);
        $email = $request->get('email');
        Assertion::notNull($email, "Email can\'t be null");
        $command = new ChangeEmailCommand($uuid, $email);
        $this->exec($command);

        return JsonResponse::create();
    }

    private function validateUuid(string $uuid): void
    {
        if (!$this->session->sameByUuid($uuid)) {
            throw new ForbiddenException();
        }
    }

    public function __construct(SessionInterface $session, CommandBus $commandBus)
    {
        parent::__construct($commandBus);
        $this->session = $session;
    }

    /**
     * @var SessionInterface
     */
    private $session;
}
