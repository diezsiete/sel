<?php


namespace App\DataTable\Type\ServicioEmpleados;


use App\DataTable\PreQueryListenerInterface;
use App\Entity\Main\Usuario;
use App\Event\Event\DataTable\PreGetResultsEvent;
use App\Service\ServicioEmpleados\Report\ReportCacheHandler;
use Omines\DataTablesBundle\Adapter\Doctrine\Event\ORMAdapterQueryEvent;
use Omines\DataTablesBundle\DataTableTypeInterface;
use Symfony\Component\Security\Core\User\UserInterface;

abstract class ServicioEmpleadosDataTableType implements DataTableTypeInterface
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

    public function preGetResultsListener(PreGetResultsEvent $event)
    {
        //para que el cache solo se ejecute al inicializar, no en paginaciones u otras acciones
        if($event->getState()->isInitial()) {
            // $this->cacheHandler->handle($this->usuario, $this->getReportEntityClass());
        }

    }

    public function setUsuario(UserInterface $usuario)
    {
        $this->usuario = $usuario;
    }

    protected abstract function getReportEntityClass(): string;
}