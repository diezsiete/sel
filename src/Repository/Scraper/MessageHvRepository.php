<?php


namespace App\Repository\Scraper;


use App\Entity\Scraper\MessageHv;
use App\Entity\Scraper\MessageHvSuccess;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method MessageHv|null find($id, $lockMode = null, $lockVersion = null)
 * @method MessageHv|null findOneBy(array $criteria, array $orderBy = null)
 * @method MessageHv[]    findAll()
 * @method MessageHv[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MessageHvRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, MessageHv::class);
    }

    public function findMessageQueue($hvId)
    {
        $hvId = is_array($hvId) ? $hvId : [$hvId];
        return $this->findInQueue($hvId, "default")
                + $this->findInQueue($hvId, "failed")
                + $this->findInQueue($hvId, "success");

    }

    public function findFailedByHvId($hvId)
    {
        return $this->createQueryBuilder('mh')
            ->join('mh.hv', 'hv')
            ->where("mh.queueName = 'failed'")
            ->andWhere('hv.id = :hvId')
            ->setParameter('hvId', $hvId)
            ->orderBy('mh.id', 'ASC')
            ->getQuery()
            ->getResult();
    }

    protected function findInQueue($hvId, $queueName)
    {
        $entityName = $queueName === "success" ? MessageHvSuccess::class : $this->_entityName;

        $qb = $this->_em->createQueryBuilder();
        return $qb->select("hv.id, mh.queueName")
            ->from($entityName, "mh")
            ->join("mh.hv", "hv")
            ->andWhere("mh.queueName = '$queueName'")
            ->andwhere($qb->expr()->in("hv.id", $hvId))
            ->groupBy("hv.id")
            ->getQuery()
            ->getResult("FETCH_KEY_PAIR");
    }


}