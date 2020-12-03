<?php

declare(strict_types=1);

namespace App\Module\User\Domain\Entity;

use Cycle\Annotated\Annotation\Column;
use Cycle\Annotated\Annotation\Entity;
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
 * @Table(
 *     indexes={
 *         @Index(columns={"token"}, unique=true)
 *     }
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
     * @Column(type="string(128)")
     *
     * @psalm-readonly
     */
    public string $token;

    /**
     * Annotations for this field placed in a mapper class
     *
     * @psalm-readonly
     */
    public DateTimeImmutable $created_at;

    /**
     * Annotations for this field placed in a mapper class
     */
    public DateTimeImmutable $updated_at;

    public function __construct(string $token)
    {
        $this->created_at = new DateTimeImmutable();
        $this->updated_at = new DateTimeImmutable();
        $this->token = $token;
    }

    public function getId(): ?string
    {
        return $this->id === null ? null : (string)$this->id;
    }
}
