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
 *     mapper="Yiisoft\Yii\Cycle\Mapper\TimestampedMapper"
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
     * Annotations for this field placed in a mapper class
     *
     * @psalm-readonly
     */
    public ?DateTimeImmutable $created_at = null;

    /**
     * Annotations for this field placed in a mapper class
     */
    public ?DateTimeImmutable $updated_at = null;

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
