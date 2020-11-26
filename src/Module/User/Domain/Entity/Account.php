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
 * @Entity(repository="App\Module\User\Domain\Repository\AccountRepository", mapper="Yiisoft\Yii\Cycle\Mapper\TimestampedMapper")
 * @Table(
 *     indexes={
 *         @Index(columns={"login"}, unique=true),
 *         @Index(columns={"identity_id"}, unique=true)
 *     }
 * )
 */
class Account
{
    /**
     * @Column(type="primary")
     */
    public ?int $id = null;

    /**
     * @Column(type="string(48)")
     */
    public string $login;

    /**
     * @Column(type="string")
     */
    public string $passwordHash;

    /**
     * Annotations for this field placed in a mapper class
     */
    public DateTimeImmutable $created_at;

    /**
     * Annotations for this field placed in a mapper class
     */
    public DateTimeImmutable $updated_at;

    /**
     * @BelongsTo(target="App\Module\User\Domain\Entity\Identity", load="eager")
     * @var \App\Module\User\Domain\Entity\Identity
     */
    public $identity;
    /** @see Identity::$id */
    public ?int $identity_id = null;

    public function __construct(string $login, string $passwordHash)
    {
        $this->login = $login;
        $this->passwordHash = $passwordHash;
        $this->created_at = new DateTimeImmutable();
        $this->updated_at = new DateTimeImmutable();
    }
}
