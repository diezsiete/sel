<?php


namespace App\DataTable\Type\ServicioEmpleados;


use App\DataTable\PreQueryListenerInterface;
use App\Entity\Main\Usuario;
use App\Service\ServicioEmpleados\Report\ReportCacheHandler;
use Omines\DataTablesBundle\Adapter\Doctrine\Event\ORMAdapterQueryEvent;
use Omines\DataTablesBundle\DataTableTypeInterface;

abstract class ServicioEmpleadosDataTableType implements DataTableTypeInterface, PreQueryListenerInterface
{
    /**
     * @var ReportCacheHandler
     */
    private $cacheHandler;

    /**
     * @var Usuario
     */
    protected $usuario;

    public function __construct(ReportCacheHandler $cacheHandler)
    {
        $this->cacheHandler = $cacheHandler;
    }

    public function preQueryListener(ORMAdapterQueryEvent $event)
    {
        $this->cacheHandler->handle($this->usuario, $this->getReportEntityClass());

    }

    public function setUsuario(Usuario $usuario)
    {
        $this->usuario = $usuario;
    }

    protected abstract function getReportEntityClass(): string;
}