<?php

declare(strict_types=1);

namespace App\Module\User\Service;

use App\Common\Domain\Writer\EntityWriter;
use App\Module\User\Api\AuthClassic;
use App\Module\User\Api\RegisterClassic;
use App\Module\User\Domain\Entity\Account;
use App\Module\User\Domain\Entity\Identity;
use App\Module\User\Domain\Repository\AccountRepository;
use Yiisoft\Security\PasswordHasher;

/**
 * @psalm-internal App\Module\User
 */
final class AccountService implements AuthClassic, RegisterClassic
{
    private AccountRepository $accountRepository;
    private PasswordHasher $passwordHasher;
    private IdentityService $identityService;
    private EntityWriter $entityWriter;

    public function __construct(
        AccountRepository $accountRepository,
        PasswordHasher $passwordHasher,
        IdentityService $identityService,
        EntityWriter $entityWriter
    ) {
        $this->accountRepository = $accountRepository;
        $this->passwordHasher = $passwordHasher;
        $this->identityService = $identityService;
        $this->entityWriter = $entityWriter;
    }

    public function login(string $login, string $password): Identity
    {
        $account = $this->accountRepository->findByLogin($login);
        if ($account === null || !$this->passwordHasher->validate($password, $account->passwordHash)) {
            throw new \RuntimeException();
        }
        return $account->identity;
    }

    /**
     * Create and save identity with account
     */
    public function register(string $login, string $password): Identity
    {
        $identity = $this->identityService->prepareIdentity([]);
        $account = $this->prepareAccount($identity, $login, $password);
        $this->entityWriter->write([$identity, $account]);
        return $identity;
    }

    private function prepareAccount(Identity $identity, string $login, string $password): Account
    {
        $passwordHash = $this->passwordHasher->hash($password);
        $account = new Account($login, $passwordHash);
        $account->identity = $identity;
        return $account;
    }
}
