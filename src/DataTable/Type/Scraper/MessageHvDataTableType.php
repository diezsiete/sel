<?php


namespace App\DataTable\Type\Scraper;


use App\DataTable\Column\ActionsColumn\ActionsColumn;
use App\Entity\Scraper\MessageHv;
use App\Entity\Scraper\MessageHvSuccess;
use Doctrine\ORM\QueryBuilder;
use Omines\DataTablesBundle\Adapter\Doctrine\ORMAdapter;
use Omines\DataTablesBundle\Column\DateTimeColumn;
use Omines\DataTablesBundle\Column\TextColumn;
use Omines\DataTablesBundle\DataTable;
use Omines\DataTablesBundle\DataTableTypeInterface;

class MessageHvDataTableType implements DataTableTypeInterface
{

    /**
     * @param DataTable $dataTable
     * @param array $options
     */
    public function configure(DataTable $dataTable, array $options)
    {

        $queueName = isset($options['queue']) ? $options['queue'] : 'error';
        $entity = $queueName === 'success' ? MessageHvSuccess::class : MessageHv::class;

        $dataTable
            ->add('identificacion', TextColumn::class, ['label' => 'id', 'field' => 'u.identificacion'])
            ->add('queue_name', TextColumn::class, ['label' => 'Queue', 'field' => 'm.queueName'])
            ->add('created_at', DateTimeColumn::class, ['label' => 'Created at', 'format' => 'Y-m-d H:i:s', 'field' => 'm.createdAt'])
            ->add('actions', ActionsColumn::class, [
                'label' => '',
                'field' => 'm.id',
                'orderable' => false,
                'actions' => [
                    [
                        'icon' => 'fas fa-file-pdf',
                        'tooltip' => 'PDF Unificado'
                    ]
                ]
            ])
            ->addOrderBy('created_at', DataTable::SORT_DESCENDING)
            ->createAdapter(ORMAdapter::class, [
                'entity' => $entity,
                'query' => function (QueryBuilder $builder) use($entity, $queueName){
                    $builder
                        ->select('m, hv, u')
                        ->from($entity, 'm')
                        ->join('m.hv', 'hv')
                        ->join('hv.usuario', 'u')
                        ->where("m.queueName = '$queueName'");
                },
            ])
        ;
    }
}