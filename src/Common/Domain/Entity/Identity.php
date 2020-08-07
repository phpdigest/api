<?php

declare(strict_types=1);

namespace App\Common\Domain\Entity;

use Cycle\Annotated\Annotation\Column;
use Cycle\Annotated\Annotation\Entity;
use Cycle\Annotated\Annotation\Table;
use Cycle\Annotated\Annotation\Table\Index;
use DateTimeImmutable;
use Yiisoft\Security\Random;
use Yiisoft\Auth\IdentityInterface;

/**
 * @Entity(repository="App\Common\Domain\Repository\IdentityRepository", mapper="Yiisoft\Yii\Cycle\Mapper\TimestampedMapper")
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
     */
    private ?int $id = null;

    /**
     * @Column(type="string(128)")
     */
    private string $token;

    /**
     * Annotations for this field placed in a mapper class
     */
    private DateTimeImmutable $created_at;

    /**
     * Annotations for this field placed in a mapper class
     */
    private DateTimeImmutable $updated_at;

    public function __construct()
    {
        $this->created_at = new DateTimeImmutable();
        $this->updated_at = new DateTimeImmutable();
        $this->resetToken();
    }

    public function getId(): ?string
    {
        return $this->id === null ? null : (string)$this->id;
    }

    public function getToken(): string
    {
        return $this->token;
    }

    public function resetToken(): void
    {
        $this->token = Random::string(128);
    }

    public function getCreatedAt(): DateTimeImmutable
    {
        return $this->created_at;
    }

    public function getUpdatedAt(): DateTimeImmutable
    {
        return $this->updated_at;
    }
}
