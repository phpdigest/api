<?php

declare(strict_types=1);

namespace App\Module\Link\Domain\Entity;

use Cycle\Annotated\Annotation\Column;
use Cycle\Annotated\Annotation\Entity;
use Cycle\Annotated\Annotation\Relation\HasMany;
use Cycle\Annotated\Annotation\Table;
use Cycle\Annotated\Annotation\Table\Index;
use DateTimeImmutable;

/**
 * @Entity(
 *     repository="App\Module\Link\Domain\Repository\UrlRepository",
 *     mapper="App\Module\Link\Domain\Mapper\UrlMapper",
 *     table="link_url"
 * )
 * @Table(
 *     indexes={
 *         @Index(columns={"host", "path", "query"}, unique=true)
 *     }
 * )
 */
class Url
{
    /**
     * @Column(type="primary")
     *
     * @psalm-readonly
     */
    public ?int $id = null;

    /**
     * @Column(type="string(10)", default="https")
     */
    public string $scheme;

    /**
     * @Column(type="string(64)")
     */
    public string $host;

    /**
     * @Column(type="string(255)", default="")
     */
    public string $path;

    /**
     * @Column(type="string(255)", default="")
     */
    public string $query;

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
     * @HasMany(target="App\Module\Link\Domain\Entity\Suggestion")
     */
    public $suggestions;

    public function __construct(string $host)
    {
        $this->host = $host;
        $this->created_at = new DateTimeImmutable();
        $this->updated_at = new DateTimeImmutable();
    }

    public function __toString(): string
    {
        $result = "{$this->scheme}://{$this->host}";
        $result .= strlen($this->path) > 1 ? $this->path : '/';
        if ($this->query !== '') {
            $result .= "?{$this->query}";
        }
        return $result;
    }
}
