<?php
/**
 * Created by PhpStorm.
 * User: guerrerojosedario
 * Date: 2018/11/06
 * Time: 7:50 PM
 */

namespace App\Hydrators;

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