<?php

namespace App\Service\Doctrine\Hydrators;

use Doctrine\ORM\Internal\Hydration\AbstractHydrator;
use PDO;

class FetchKeyPairHydrator extends AbstractHydrator
{

    /**
     * Hydrates all rows from the current statement instance at once.
     *
     * @return array
     */
    protected function hydrateAllData()
    {
        return $this->_stmt->fetchAll(PDO::FETCH_KEY_PAIR);
    }
}