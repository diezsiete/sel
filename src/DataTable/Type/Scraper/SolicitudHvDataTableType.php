<?php


namespace App\DataTable\Type\Scraper;


use App\DataTable\Column\ActionsColumn\ActionsColumn;
use App\Entity\Scraper\MessageHv;
use App\Entity\Scraper\MessageHvSuccess;
use App\Entity\Scraper\Solicitud;
use App\Repository\Scraper\SolicitudRepository;
use Doctrine\ORM\QueryBuilder;
use Omines\DataTablesBundle\Adapter\Doctrine\ORMAdapter;
use Omines\DataTablesBundle\Column\DateTimeColumn;
use Omines\DataTablesBundle\Column\MapColumn;
use Omines\DataTablesBundle\Column\TextColumn;
use Omines\DataTablesBundle\DataTable;
use Omines\DataTablesBundle\DataTableTypeInterface;

class SolicitudHvDataTableType implements DataTableTypeInterface
{

    /**
     * @var SolicitudRepository
     */
    private $solicitudRepository;

    public function __construct(SolicitudRepository $solicitudRepository)
    {
        $this->solicitudRepository = $solicitudRepository;
    }

    /**
     * @param DataTable $dataTable
     * @param array $options
     */
    public function configure(DataTable $dataTable, array $options)
    {

        //$queueName = isset($options['queue']) ? $options['queue'] : 'failed';

        $dataTable
            ->add('identificacion', TextColumn::class, ['label' => 'id', 'field' => 'u.identificacion'])
            ->add('estado', MapColumn::class, [
                'label' => 'Estado',
                'field' => 's.estado',
                'map' => $this->solicitudRepository->getEstadoArray()
            ])
            ->add('createdAt', DateTimeColumn::class, ['label' => 'Created at', 'format' => 'Y-m-d H:i:s'])
            ->add('actions', ActionsColumn::class, [
                'label' => '',
                'field' => 'u.identificacion',
                'orderable' => false,
                'actions' => [
                    [
                        'modal' => '#modalTest',
                        //'confirm' => ['admin_vacante_borrar', ['vacante' => '[0].id']],
                        'icon' => 'far fa-envelope',
                        'tooltip' => 'Message',
                        'data-id' => '.id',
                        'data-queue' => '.queueName'
                    ],
                    [
                        'icon' => 'fas fa-upload',
                        'tooltip' => 'Cargar a novasoft',
                        'data-id' => 'hv.id',
                        'class' => 'scraper'
                    ],
                ]
            ])
            ->addOrderBy('created_at', DataTable::SORT_DESCENDING)
            ->createAdapter(ORMAdapter::class, [
                'entity' => Solicitud::class,
                'query' => function (QueryBuilder $builder) {
                    $builder
                        ->select('s, hv, u')
                        ->from(Solicitud::class, 's')
                        ->join('s.hv', 'hv')
                        ->join('hv.usuario', 'u');
                },
            ])
        ;
    }
}