<?php


namespace App\Repository\Hv;


use App\Entity\Hv\Hv;
use App\Entity\Hv\HvEntity;

trait HvChildRepository
{
    /**
     * @param Hv $hv
     * @param HvEntity $entity
     * @return HvEntity[]
     */
    public function findSiblings(Hv $hv, HvEntity $entity)
    {
        $qb = $this->createQueryBuilder('e');
        $qb->andWhere($qb->expr()->eq('e.hv', ':hv'))->setParameter('hv', $hv);
        if($entity->getId()) {
            $qb->andWhere($qb->expr()->neq('e', ':e'))->setParameter('e', $entity);
        }

        return $qb->getQuery()->getResult();
    }
}