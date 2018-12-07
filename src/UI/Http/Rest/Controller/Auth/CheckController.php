<?php

declare(strict_types=1);

namespace Micro\User\UI\Http\Rest\Controller\Auth;

use Assert\Assertion;
use Micro\User\Application\Command\User\SignIn\SignInCommand;
use Micro\User\Application\Query\Auth\GetToken\GetTokenQuery;
use Micro\User\Domain\User\Exception\InvalidCredentialsException;
use Micro\User\UI\Http\Rest\Controller\CommandQueryController;
use Nelmio\ApiDocBundle\Annotation\Security;
use Swagger\Annotations as SWG;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

final class CheckController extends CommandQueryController
{
    /**
     * @Route(
     *     "/auth_check",
     *     name="auth_check",
     *     methods={"POST"},
     *     requirements={
     *      "_username": "\w+",
     *      "_password": "\w+"
     *     }
     * )
     * @SWG\Response(
     *     response=200,
     *     description="Login success"
     * )
     * @SWG\Response(
     *     response=400,
     *     description="Bad request"
     * )
     * @SWG\Response(
     *     response=401,
     *     description="ad credentials"
     * )
     * @SWG\Parameter(
     *     name="credentials",
     *     type="object",
     *     in="body",
     *     schema=@SWG\Schema(type="object",
     *         @SWG\Property(property="_password", type="string"),
     *         @SWG\Property(property="_username", type="string")
     *     )
     * )
     *
     * @SWG\Tag(name="Auth")
     *
     * @Security(name="Bearer")
     *
     * @throws InvalidCredentialsException
     * @throws \Assert\AssertionFailedException
     */
    public function __invoke(Request $request): JsonResponse
    {
        $username = $request->get('_username');
        Assertion::notNull($username, 'Username cant\'t be empty');
        $signInCommand = new SignInCommand(
            $username,
            $request->get('_password')
        );
        $this->exec($signInCommand);

        return JsonResponse::create(
            [
                'token' => $this->ask(new GetTokenQuery($username)),
            ]
        );
    }
}
