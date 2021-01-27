<?php

declare(strict_types=1);

namespace App\Module\User\Command;

use App\Module\User\Api\RegisterClassic;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Throwable;
use Yiisoft\Yii\Console\ExitCode;
use Yiisoft\Yii\Cycle\Data\Writer\EntityWriter;

class UserCreateCommand extends Command
{
    protected static $defaultName = 'user/create';

    private RegisterClassic $registerService;
    private EntityWriter $entityWriter;

    public function __construct(RegisterClassic $registerService, EntityWriter $entityWriter)
    {
        $this->registerService = $registerService;
        $this->entityWriter = $entityWriter;
        parent::__construct();
    }

    public function configure(): void
    {
        $this
            ->setDescription('Creates a user')
            ->setHelp('This command allows you to create a user')
            ->addArgument('username', InputArgument::REQUIRED, 'Username')
            ->addArgument('password', InputArgument::REQUIRED, 'Password');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $username = (string)$input->getArgument('username');
        $password = (string)$input->getArgument('password');

        $user = $this->registerService->register($username, $password);
        try {
            $this->entityWriter->write([$user]);
            $io->success('User created.');
        } catch (Throwable $t) {
            $io->error($t->getMessage());
            return (int)$t->getCode() ?: ExitCode::UNSPECIFIED_ERROR;
        }
        return ExitCode::OK;
    }
}
