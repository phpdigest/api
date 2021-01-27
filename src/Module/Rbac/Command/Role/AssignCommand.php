<?php

declare(strict_types=1);

namespace App\Module\Rbac\Command\Role;

use Exception;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ConfirmationQuestion;
use Symfony\Component\Console\Style\SymfonyStyle;
use Throwable;
use Yiisoft\Auth\IdentityRepositoryInterface;
use Yiisoft\Rbac\Manager;
use Yiisoft\Rbac\Role;
use Yiisoft\Rbac\StorageInterface;
use Yiisoft\Yii\Console\ExitCode;

class AssignCommand extends Command
{
    private IdentityRepositoryInterface $identityRepository;
    private Manager $manager;
    private StorageInterface $storage;

    public static $defaultName = 'rbac/role/assign';

    public function __construct(
        IdentityRepositoryInterface $identityRepository,
        Manager $manager,
        StorageInterface $storage
    ) {
        $this->identityRepository = $identityRepository;
        $this->manager = $manager;
        $this->storage = $storage;
        parent::__construct();
    }

    public function configure(): void
    {
        $this
            ->setDescription('Assign RBAC role to given user')
            ->setHelp('This command allows you to assign RBAC role to user')
            ->addArgument('role', InputArgument::REQUIRED, 'RBAC role')
            ->addArgument('userId', InputArgument::REQUIRED, 'User id');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $roleName = $input->getArgument('role');
        $userId = $input->getArgument('userId');

        try {
            $user = $this->identityRepository->findIdentity($userId);
            if (null === $user) {
                throw new Exception('Can\'t find user', ExitCode::NOUSER);
            }

            $role = $this->storage->getRoleByName($roleName);

            if (null === $role) {
                $helper = $this->getHelper('question');
                $question = new ConfirmationQuestion('Role doesn\'t exist. Create new one? ', false);

                if (!$helper->ask($input, $output, $question)) {
                    return ExitCode::OK;
                }

                $role = new Role($roleName);
                $this->manager->addRole($role);
            }

            $this->manager->assign($role, $user->getId());

            $io->success('Role was assigned to given user.');
        } catch (Throwable $t) {
            $io->error($t->getMessage());
            return (int)$t->getCode() ?: ExitCode::UNSPECIFIED_ERROR;
        }

        return ExitCode::OK;
    }
}
