<?php

declare(strict_types=1);

namespace App\Module\User\Domain\Entity;

use Cycle\Annotated\Annotation\Column;
use Cycle\Annotated\Annotation\Entity;
use Cycle\Annotated\Annotation\Relation\BelongsTo;
use Cycle\Annotated\Annotation\Table;
use Cycle\Annotated\Annotation\Table\Index;
use DateTimeImmutable;

/**
 * @psalm-internal App\Module\User
 *
 * @Entity(
 *     role="user_account",
 *     repository="App\Module\User\Domain\Repository\AccountRepository",
 *     mapper="App\Module\User\Domain\Mapper\AccountMapper"
 * )
 * @Table(
 *     indexes={
 *         @Index(columns={"username"}, unique=true),
 *         @Index(columns={"identity_id"}, unique=true)
 *     }
 * )
 */
class Account
{
    /**
     * @Column(type="primary")
     *
     * @psalm-readonly
     */
    public ?int $id = null;

    /**
     * @Column(type="string(48)")
     */
    public string $username;

    /**
     * @Column(type="string")
     */
    public string $passwordHash;

    /**
     * @Column(type="datetime")
     *
     * @psalm-readonly
     */
    public DateTimeImmutable $created_at;

    /**
     * @Column(type="datetime")
     */
    public DateTimeImmutable $updated_at;

    /**
     * @BelongsTo(target="App\Module\User\Domain\Entity\Identity", load="eager")
     * @var Identity
     */
    public $identity;
    /** @see Identity::$id */
    public ?int $identity_id = null;

    public function __construct(string $username, string $passwordHash)
    {
        $this->username = $username;
        $this->passwordHash = $passwordHash;
        $this->created_at = new DateTimeImmutable();
        $this->updated_at = new DateTimeImmutable();
    }
}
