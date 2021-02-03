<?php

declare(strict_types=1);

namespace App\Module\Link\Domain\Entity;

use App\Module\User\Domain\Entity\Identity;
use Cycle\Annotated\Annotation\Column;
use Cycle\Annotated\Annotation\Entity;
use Cycle\Annotated\Annotation\Relation\BelongsTo;
use Cycle\Annotated\Annotation\Table;
use Cycle\ORM\Promise\Reference;
use DateTimeImmutable;

/**
 * @Entity(
 *     repository="App\Module\Link\Domain\Repository\SuggestionRepository",
 *     mapper="App\Module\Link\Domain\Mapper\SuggestionMapper",
 *     table="link_suggestion"
 * )
 * @Table()
 */
class Suggestion
{
    /**
     * @Column(type="primary")
     *
     * @psalm-readonly
     */
    public ?int $id = null;

    /**
     * @var null|Url|Reference
     * @psalm-var null|Url
     * @BelongsTo(target="App\Module\Link\Domain\Entity\Url", nullable=false, load="eager")
     */
    public $url;

    /**
     * @Column(type="text", nullable=true)
     */
    public ?string $description = null;

    /**
     * @Column(type="string(32)", nullable=true, default=null)
     */
    public ?string $source = null;

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
     * @var null|Identity|Reference
     * @psalm-var null|Identity
     * @BelongsTo(target="App\Module\User\Domain\Entity\Identity", nullable=false)
     */
    public $identity = null;

    public ?int $identity_id = null;

    public function __construct()
    {
        $this->created_at = new DateTimeImmutable();
        $this->updated_at = new DateTimeImmutable();
    }
}
