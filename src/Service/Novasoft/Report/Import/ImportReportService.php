<?php


namespace App\Service\Novasoft\Report\Import;


use App\Entity\Convenio;
use App\Repository\ConvenioRepository;
use App\Service\Novasoft\Report\Report\TrabajadoresActivosReport;
use DateTimeInterface;
use Psr\Container\ContainerInterface;
use SSRS\SSRSReportException;
use Symfony\Contracts\Service\ServiceSubscriberInterface;

class ImportReportService implements ServiceSubscriberInterface
{
    /**
     * @var ContainerInterface
     */
    private $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * @param null|Convenio|Convenio[] $convenio
     * @param DateTimeInterface|null $fecha
     * @throws SSRSReportException
     */
    public function trabajadoresActivos($convenio = null, ?DateTimeInterface $fecha = null)
    {
        if(!$convenio) {
            $convenios = $this->container->get(ConvenioRepository::class)->findAllActivos();
        } else {
            $convenios = is_array($convenio) ? $convenio : [$convenio];
        }

        $report = $this->container->get(TrabajadoresActivosReport::class);
        $importHelper = $this->container->get(ImportReportHelper::class);
        foreach($convenios as $convenio) {
            $result = $report
                ->setConvenio($convenio)
                ->setFecha($fecha)
                ->renderMap();

            $importHelper->import($result);
        }

    }


    public static function getSubscribedServices()
    {
        return [
            ConvenioRepository::class,
            TrabajadoresActivosReport::class,
            ImportReportHelper::class
        ];
    }
}