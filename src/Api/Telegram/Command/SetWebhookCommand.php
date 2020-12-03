<?php

declare(strict_types=1);

namespace App\Api\Telegram\Command;

use GuzzleHttp\Client;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Yiisoft\Http\Status;

final class SetWebhookCommand extends Command
{
    public static $defaultName = 'telegram/set-webhook';

    private string $token;

    private LoggerInterface $logger;

    public function __construct(string $token, LoggerInterface $logger)
    {
        parent::__construct();
        $this->token = $token;
        $this->logger = $logger;
    }

    protected function configure(): void
    {
        $this->addArgument('address', InputArgument::REQUIRED, 'Address where Telegram will send callbacks');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $url = $input->getArgument('address');

        $client = new Client();
        $response = $client->post("https://api.telegram.org/bot{$this->token}/setWebhook",
            [
                'form_params' => [
                    'url' => $url,
                ],
            ]);

        if ($response->getStatusCode() !== Status::OK) {
            $this->logger->error('Error was occurred while sending request', [
                'response' => $response,
            ]);

            return 1;
        }

        $this->logger->info("Telegram webhook address was changed to \"{$url}\"");
        $output->writeln("Telegram webhook address was changed to \"{$url}\"");

        return 0;
    }
}
