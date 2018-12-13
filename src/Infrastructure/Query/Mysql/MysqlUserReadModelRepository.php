<?php

declare(strict_types=1);

namespace Micro\User\Infrastructure\Query\Mysql;

use Doctrine\ORM\EntityManagerInterface;
use Micro\User\Domain\Query\Projections\UserViewInterface;
use Micro\User\Domain\Query\Repository\UserReadModelRepositoryInterface as UserReadModel;
use Micro\User\Domain\Repository\CheckUserByEmailInterface as CheckUserEmail;
use Micro\User\Domain\ValueObject\Email;
use Micro\User\Infrastructure\Query\Projections\UserView;
use Micro\User\Infrastructure\Share\Query\Repository\MysqlRepository as Repository;
use Ramsey\Uuid\UuidInterface;

class MysqlUserReadModelRepository extends Repository implements UserReadModel, CheckUserEmail
{
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->class = UserView::class;
        parent::__construct($entityManager);
    }

    /**
     * @throws \Micro\User\Domain\Shared\Query\Exception\NotFoundException
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function oneByUuid(UuidInterface $uuid): UserViewInterface
    {
        $qb = $this->repository
            ->createQueryBuilder('user')
            ->where('user.uuid = :uuid')
            ->setParameter('uuid', $uuid->getBytes())
        ;

        return $this->oneOrException($qb);
    }

    /**
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function existsEmail(Email $email): ?UuidInterface
    {
        $userId = $this->repository
            ->createQueryBuilder('user')
            ->select('user.uuid')
            ->where('user.credentials.email = :email')
            ->setParameter('email', (string) $email)
            ->getQuery()
            ->getOneOrNullResult()
        ;

        return $userId['uuid'] ?? null;
    }

    /**
     * @throws \Micro\User\Domain\Shared\Query\Exception\NotFoundException
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function oneByEmail(Email $email): UserViewInterface
    {
        $qb = $this->repository
            ->createQueryBuilder('user')
            ->where('user.credentials.email = :email')
            ->setParameter('email', $email->toString())
        ;

        return $this->oneOrException($qb);
    }

    public function add(UserViewInterface $userRead): void
    {
        $this->register($userRead);
    }
}
