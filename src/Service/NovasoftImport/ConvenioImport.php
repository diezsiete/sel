<?php


namespace App\Service\NovasoftImport;


use App\Entity\Convenio;
use App\Service\NovasoftSsrs\NovasoftSsrs;
use App\Service\ReportesServicioEmpleados;
use Doctrine\ORM\EntityManagerInterface;

class ConvenioImport
{
    /**
     * @var ReportesServicioEmpleados
     */
    private $reportesServicioEmpleados;
    /**
     * @var EntityManagerInterface
     */
    private $em;

    public function __construct(ReportesServicioEmpleados $reportesServicioEmpleados, EntityManagerInterface $entityManager)
    {
        $this->reportesServicioEmpleados = $reportesServicioEmpleados;
        $this->em = $entityManager;
    }

    /**
     * @return array
     * @throws \SSRS\SSRSReportException
     */
    public function import()
    {
        $novasoftConvenios = $this->reportesServicioEmpleados->getConvenios();
        return $this->persistNovasoftConvenios($novasoftConvenios);
    }

    /**
     * @param Convenio $convenio
     * @return bool
     */
    public function persistConvenio(Convenio $convenio)
    {
        $persisted = true;
        $convenioDb = $this->em->getRepository(Convenio::class)->find($convenio->getCodigo());
        if($convenioDb) {
            $persisted = false;
            $convenioDb
                ->setNombre($convenio->getNombre())
                ->setCodigo($convenio->getCodigo())
                ->setCodigoCliente($convenio->getCodigoCliente())
                ->setDireccion($convenio->getDireccion());
        } else {
            $this->em->persist($convenio);
        }
        return $persisted;
    }

    private function persistNovasoftConvenios($convenios)
    {
        $conveniosPersisted = [];
        foreach($convenios as $convenio) {
            $convenioDb = $this->em->getRepository(Convenio::class)->find($convenio->getCodigo());
            if($convenioDb) {
                $convenioDb
                    ->setNombre($convenio->getNombre())
                    ->setCodigo($convenio->getCodigo())
                    ->setCodigoCliente($convenio->getCodigoCliente())
                    ->setDireccion($convenio->getDireccion());
                $conveniosPersisted[] = $convenioDb;
            }else {
                $this->em->persist($convenio);
                $convenios[] = $convenio;
            }
        }
        $this->em->flush();
        return $convenios;
    }

}