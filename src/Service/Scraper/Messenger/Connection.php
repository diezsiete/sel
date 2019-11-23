<?php


namespace App\Service\Scraper\Messenger;


use Doctrine\Common\Persistence\ManagerRegistry;

class Connection
{
    /**
     * @var ManagerRegistry
     */
    private $doctrine;

    public function __construct(ManagerRegistry $managerRegistry)
    {
        $this->doctrine = $managerRegistry;
    }

    public function hasFailed($usuarioId)
    {

    }
}