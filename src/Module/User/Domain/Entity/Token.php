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
 *     role="user_token",
 *     repository="App\Module\User\Domain\Repository\IdentityWithTokenRepository"
 * )
 * @Table(
 *     indexes={
 *         @Index(columns={"type", "token"}, unique=true)
 *     }
 * )
 */
class Token
{
    public const TYPE_WEB = 'web';
    public const TYPE_TELEGRAM = 'telegram';
    public const TYPE_API = 'api';

    /**
     * @Column(type="primary")
     */
    public ?int $id = null;

    /**
     * @Column(type="string")
     */
    public string $token;

    /**
     * @Column(type="string(16)")
     */
    public string $type;

    /**
     * @Column(type="datetime")
     *
     * @psalm-readonly
     */
    public DateTimeImmutable $created_at;

    /**
     * @BelongsTo(target="App\Module\User\Domain\Entity\Identity", load="eager")
     * @var Identity
     */
    public $identity;
    /** @see Identity::$id */
    public ?int $identity_id = null;

    public function __construct(string $token, string $type)
    {
        $this->token = $token;
        $this->type = $type;
        $this->created_at = new DateTimeImmutable();
    }
}
