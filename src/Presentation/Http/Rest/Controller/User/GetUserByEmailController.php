<?php

declare(strict_types=1);

namespace Micro\User\Presentation\Http\Rest\Controller\User;

use Assert\Assertion;
use Micro\User\Application\Query\Item;
use Micro\User\Application\Query\User\FindByEmail\FindByEmailQuery;
use Micro\User\Presentation\Http\Rest\Controller\QueryController;
use Nelmio\ApiDocBundle\Annotation\Security;
use Swagger\Annotations as SWG;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

final class GetUserByEmailController extends QueryController
{
    /**
     * @Route(
     *     "/user/{email}",
     *     name="find_user",
     *     methods={"GET"}
     * )
     * @SWG\Response(
     *     response=200,
     *     description="Returns the user of the given email"
     * )
     * @SWG\Response(
     *     response=400,
     *     description="Bad request"
     * )
     * @SWG\Response(
     *     response=404,
     *     description="Not found"
     * )
     * @SWG\Parameter(
     *     name="email",
     *     in="path",
     *     type="string",
     *     description="email"
     * )
     *
     * @SWG\Tag(name="User")
     *
     * @Security(name="Bearer")
     *
     * @throws \Assert\AssertionFailedException
     */
    public function __invoke(Request $request): JsonResponse
    {
        $email = $request->get('email');
        Assertion::notNull($email, "Email can\'t be null");
        $command = new FindByEmailQuery($email);

        /** @var Item $user */
        $user = $this->ask($command);

        return $this->json($user);
    }
}
