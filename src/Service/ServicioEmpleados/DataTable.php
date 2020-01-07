<?php


namespace App\Service\ServicioEmpleados;


use App\DataTable\Type\AutoliquidacionEmpleadoDataTableType;
use App\DataTable\Type\ReporteNominaDataTableType;
use App\Entity\Main\Usuario;
use Omines\DataTablesBundle\DataTableFactory;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Security;

class DataTable
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


    public function __construct(DataTableFactory $dataTableFactory, RequestStack $requestStack, Security $security)
    {
        $this->dataTableFactory = $dataTableFactory;
        $this->request = $requestStack->getCurrentRequest();

        $this->security = $security;
    }

    /**
     * @param Request $request
     * @param Usuario $usuario
     * @return \Omines\DataTablesBundle\DataTable
     */
    public function comprobantes(array $options = [])
    {
        $options = array_merge([
            'searching' => false,
            'paging' => false
        ], $options);

        $id = $this->security->getUser()->getId();

        return $this->dataTableFactory
            ->createFromType(ReporteNominaDataTableType::class, ['id' => $id], $options)
            ->handleRequest($this->request);
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