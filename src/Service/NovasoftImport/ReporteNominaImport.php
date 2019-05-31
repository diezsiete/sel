<?php


namespace App\Service\NovasoftImport;


use App\Entity\ReporteNomina;
use App\Repository\ReporteNominaRepository;
use Doctrine\ORM\EntityManagerInterface;

class ReporteNominaImport
{
    /**
     * @var EntityManagerInterface
     */
    private $em;
    /**
     * @var ReporteNominaRepository
     */
    private $reporteNominaRepository;

    public function __construct(EntityManagerInterface $em, ReporteNominaRepository $reporteNominaRepository)
    {
        $this->em = $em;
        $this->reporteNominaRepository = $reporteNominaRepository;
    }

    /**
     * @param ReporteNomina $reporteNomina
     * @return ReporteNomina|null
     */
    public function exists(ReporteNomina $reporteNomina)
    {
        $reporteNominaDb = $this->reporteNominaRepository->findByFecha($reporteNomina->getUsuario(), $reporteNomina->getFecha());
        return $reporteNominaDb;
    }

    public function insert(ReporteNomina $reporteNomina)
    {
        $this->em->persist($reporteNomina);
        return true;
    }

    public function update(ReporteNomina $target, ReporteNomina $external)
    {
        return true;
    }
}