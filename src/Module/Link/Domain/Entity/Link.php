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
 *     repository="App\Module\Link\Domain\Repository\LinkRepository",
 *     mapper="App\Module\Link\Domain\Mapper\LinkMapper",
 *     table="link_suggestion"
 * )
 * @Table()
 */
class Link
{
    /**
     * @Column(type="primary")
     */
    private ?int $id = null;

    /**
     * @Column(type="string(255)")
     */
    private string $url;

    /**
     * @Column(type="string(255)", nullable=true)
     */
    private ?string $description = null;

    /**
     * @Column(type="datetime")
     */
    private DateTimeImmutable $created_at;

    /**
     * @Column(type="datetime")
     */
    private DateTimeImmutable $updated_at;

    /**
     * @var null|Identity|Reference
     * @BelongsTo(target="App\Module\User\Domain\Entity\Identity", nullable=false)
     */
    private $identity = null;

    private ?int $identity_id = null;

    public function __construct()
    {
        $this->created_at = new DateTimeImmutable();
        $this->updated_at = new DateTimeImmutable();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUrl(): string
    {
        return $this->url;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setUrl(string $url): void
    {
        $this->url = $url;
    }

    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    public function setIdentity(Identity $identity): void
    {
        $this->identity = $identity;
    }

    public function getIdentity(): ?Identity
    {
        return $this->identity;
    }
}
