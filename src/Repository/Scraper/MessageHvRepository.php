<?php


namespace App\Repository\Scraper;


use App\Entity\Scraper\MessageHv;
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
}