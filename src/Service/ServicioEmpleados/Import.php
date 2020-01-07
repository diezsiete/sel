<?php


namespace App\Service\ServicioEmpleados;


use App\Entity\Main\Usuario;
use App\Repository\Main\ConvenioRepository;
use App\Repository\Main\ReporteNominaRepository;
use App\Service\NovasoftSsrs\NovasoftSsrs;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class Import
{
    /**
     * @var NovasoftSsrs
     */
    private $novasoftSsrs;
    /**
     * @var ReporteNominaRepository
     */
    private $reporteNominaRepository;
    /**
     * @var \App\Repository\Main\ConvenioRepository
     */
    private $convenioRepository;
    /**
     * @var EntityManagerInterface
     */
    private $em;

    public function __construct(NovasoftSsrs $novasoftSsrs, ReporteNominaRepository $reporteNominaRepository,
                                ConvenioRepository $convenioRepository, EntityManagerInterface $em)
    {
        $this->novasoftSsrs = $novasoftSsrs;
        $this->reporteNominaRepository = $reporteNominaRepository;
        $this->convenioRepository = $convenioRepository;
        $this->em = $em;
    }


    public function nomina(Usuario $usuario)
    {
        $desde = DateTime::createFromFormat('Y-m-d', (new DateTime())->format('Y-m') . '-01');
        $hasta = DateTime::createFromFormat('Y-m-d', (new DateTime())->format('Y-m-t'));

        $reportesNomina = $this->novasoftSsrs
            ->setSsrsDb($this->getSsrsDb($usuario))
            ->getReporteNomina($usuario, $desde, $hasta);

        if(count($reportesNomina)) {
            foreach ($reportesNomina as $reporteNomina) {
                $reporteNominaDb = $this->reporteNominaRepository->findByFecha($reporteNomina->getUsuario(), $reporteNomina->getFecha());
                if ($reporteNominaDb) {
                    $this->em->remove($reporteNominaDb);
                }
                $this->em->persist($reporteNomina);
            }
            $this->em->flush();
        }
    }

    /**
     * @return string|null
     */
    private function getSsrsDb(Usuario $usuario)
    {
        $convenio = $this->convenioRepository->findConvenioByIdent($usuario->getIdentificacion());
        if($convenio) {
            return $convenio->getSsrsDb();
        } else {
            throw new NotFoundHttpException("not found");
        }
    }
}