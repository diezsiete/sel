<?php

namespace App\Service\ServicioEmpleados;

use App\DataTable\SelDataTableFactory;
use App\DataTable\Type\AutoliquidacionEmpleadoDataTableType;
use App\DataTable\Type\ServicioEmpleados\NominaDataTableType;
use Omines\DataTablesBundle\DataTableFactory;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Security\Core\Security;

class DataTableBuilder
{
    /**
     * @var DataTableFactory
     */
    private $dataTableFactory;
    /**
     * @var Request
     */
    private $request;
    /**
     * @var Security
     */
    private $security;


    public function __construct(SelDataTableFactory $dataTableFactory, RequestStack $requestStack, Security $security)
    {
        $this->dataTableFactory = $dataTableFactory;
        $this->request = $requestStack->getCurrentRequest();

        $this->security = $security;
    }



    public function nomina($options = [])
    {
        $options = array_merge([
            'searching' => false,
        ], $options);

        $table = $this->dataTableFactory
            ->createFromServicioEmpleadosType(NominaDataTableType::class, $this->security->getUser(), $options)
            ->handleRequest($this->request);
        return $table;
    }

    public function certificadosAportes(array $options = [])
    {
        $options = array_merge([
            'searching' => false,
            'paging' => false
        ], $options);

        $id = $this->security->getUser()->getId();

        return $this->dataTableFactory
            ->createFromType(AutoliquidacionEmpleadoDataTableType::class, ['id' => $id], $options)
            ->handleRequest($this->request);
    }
}