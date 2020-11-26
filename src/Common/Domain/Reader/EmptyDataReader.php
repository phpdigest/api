<?php

declare(strict_types=1);

namespace App\Common\Domain\Reader;

use Generator;
use Yiisoft\Data\Reader\DataReaderInterface;
use Yiisoft\Data\Reader\Filter\FilterInterface;
use Yiisoft\Data\Reader\Filter\FilterProcessorInterface;
use Yiisoft\Data\Reader\Sort;

final class EmptyDataReader implements DataReaderInterface
{
    private ?int $limit = null;
    private ?int $offset = null;
    private ?Sort $sorting = null;

    public function getSort(): ?Sort
    {
        return $this->sorting;
    }

    /**
     * @psalm-mutation-free
     */
    public function withLimit(int $limit): self
    {
        $clone = clone $this;
        $clone->limit = $limit;
        return $clone;
    }

    /**
     * @psalm-mutation-free
     */
    public function withOffset(int $offset): self
    {
        $clone = clone $this;
        $clone->offset = $offset;
        return $clone;
    }

    /**
     * @psalm-mutation-free
     */
    public function withSort(?Sort $sorting): self
    {
        $clone = clone $this;
        $clone->sorting = $sorting;
        return $clone;
    }

    public function count(): int
    {
        return 0;
    }

    public function read(): iterable
    {
        return [];
    }

    public function readOne()
    {
        return null;
    }

    public function getIterator(): Generator
    {
        yield from [];
    }

    /**
     * @psalm-mutation-free
     */
    public function withFilter(FilterInterface $filter): self
    {
        return $this;
    }

    /**
     * @psalm-mutation-free
     */
    public function withFilterProcessors(FilterProcessorInterface ...$filterProcessors): self
    {
        return $this;
    }
}
