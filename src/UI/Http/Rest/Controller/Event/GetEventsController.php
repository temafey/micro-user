<?php

declare(strict_types=1);

namespace Micro\User\UI\Http\Rest\Controller\Event;

use Assert\Assertion;
use Micro\User\Application\Query\Collection;
use Micro\User\Application\Query\Event\GetEvents\GetEventsQuery;
use Micro\User\UI\Http\Rest\Controller\QueryController;
use Nelmio\ApiDocBundle\Annotation\Security;
use Swagger\Annotations as SWG;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class GetEventsController extends QueryController
{
    /**
     * @Route(
     *     path="/events",
     *     name="events",
     *     methods={"GET"}
     * )
     *
     * @SWG\Response(
     *     response=201,
     *     description="User created successfully"
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
     *     name="user",
     *     type="object",
     *     in="body",
     *     schema=@SWG\Schema(type="object",
     *         @SWG\Property(property="uuid", type="string"),
     *         @SWG\Property(property="email", type="string")
     *     )
     * )
     *
     * @SWG\Tag(name="Events")
     *
     * @Security(name="Bearer")
     *
     * @throws \Assert\AssertionFailedException
     */
    public function __invoke(Request $request): JsonResponse
    {
        $page = $request->get('page', 1);
        $limit = $request->get('limit', 50);

        Assertion::numeric($page, 'Page number must be an integer');
        Assertion::numeric($limit, 'Limit results must be an integer');

        $query = new GetEventsQuery((int) $page, (int) $limit);

        /** @var Collection $response */
        $response = $this->ask($query);

        return $this->jsonCollection($response, true);
    }
}
