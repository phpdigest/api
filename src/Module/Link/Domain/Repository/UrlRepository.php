<?php

declare(strict_types=1);

namespace App\Module\Link\Domain\Repository;

use App\Common\Domain\BaseRepository;
use App\Module\Link\Domain\Entity\Url;
use Cycle\ORM\Select;

/**
 * @method Url findOne(array $scope = [])
 * @method Url findByPK($id)
 */
final class UrlRepository extends BaseRepository
{
    public function __construct(Select $select)
    {
        parent::__construct($select);
    }
}
