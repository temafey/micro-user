<?php

declare(strict_types=1);

namespace Micro\User\Presentation\Cli\Command;

use League\Tactician\CommandBus;
use Micro\User\Application\Command\User\SignUp\SignUpCommand as CreateUser;
use Ramsey\Uuid\Uuid;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CreateUserCommand extends Command
{
    /**
     * @var CommandBus
     */
    private $commandBus;

    public function __construct(CommandBus $commandBus)
    {
        parent::__construct();
        $this->commandBus = $commandBus;
    }

    protected function configure(): void
    {
        $this
            ->setName('app:create-user')
            ->setDescription('Given a uuid and email, generates a new user.')
            ->addArgument('email', InputArgument::REQUIRED, 'User email')
            ->addArgument('password', InputArgument::REQUIRED, 'User password')
            ->addArgument('uuid', InputArgument::OPTIONAL, 'User Uuid')
        ;
    }

    /**
     * @throws \Exception
     */
    protected function execute(InputInterface $input, OutputInterface $output): void
    {
        $uuid = ($input->getArgument('uuid') ?: Uuid::uuid4()->toString());
        $email = $input->getArgument('email');
        $password = $input->getArgument('password');

        $command = $this->createUserCommand($uuid, $email, $password);
        $this->commandBus->handle($command);

        $output->writeln('<info>User Created: </info>');
        $output->writeln('');
        $output->writeln("Uuid: $uuid");
        $output->writeln("Email: $email");
    }

    private function createUserCommand(string $uuid, string $email, string $password): CreateUser
    {
        return new CreateUser($uuid, $email, $password);
    }
}
