<?php

declare(strict_types=1);

namespace App\Module\User\Domain\Entity;

use Cycle\Annotated\Annotation\Column;
use Cycle\Annotated\Annotation\Entity;
use Cycle\Annotated\Annotation\Relation\HasOne;
use Cycle\Annotated\Annotation\Table;
use Cycle\Annotated\Annotation\Table\Index;
use DateTimeImmutable;
use Yiisoft\Auth\IdentityInterface;

/**
 * @psalm-internal App\Module\User
 *
 * @Entity(
 *     role="user_identity",
 *     repository="App\Module\User\Domain\Repository\IdentityRepository",
 *     mapper="App\Module\User\Domain\Mapper\IdentityMapper"
 * )
 */
class Identity implements IdentityInterface
{
    /**
     * @Column(type="primary")
     *
     * @psalm-readonly
     */
    public ?int $id = null;

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
     * @HasOne(
     *     target="App\Module\User\Domain\Entity\Account",
     *     load="lazy",
     *     innerKey="id",
     *     outerKey="identity_id",
     *     nullable=true,
     *     fkCreate=false,
     *     indexCreate=false
     * )
     * @var Account
     */
    public $account;

    public function __construct()
    {
        $this->created_at = new DateTimeImmutable();
        $this->updated_at = new DateTimeImmutable();
    }

    public function getId(): ?string
    {
        return $this->id === null ? null : (string)$this->id;
    }
}
