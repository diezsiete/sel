<?php


namespace App\DataTable\Type\Hv;


use App\Entity\Hv\Hv;
use App\Entity\Main\Usuario;
use Doctrine\ORM\QueryBuilder;
use Omines\DataTablesBundle\Adapter\Doctrine\ORMAdapter;
use Omines\DataTablesBundle\Column\TextColumn;
use Omines\DataTablesBundle\DataTable;
use Omines\DataTablesBundle\DataTableTypeInterface;

class HvDataTableType implements DataTableTypeInterface
{

    /**
     * @param DataTable $dataTable
     * @param array $options
     */
    public function configure(DataTable $dataTable, array $options)
    {
        $dataTable
            ->add('identificacion', TextColumn::class, ['label' => 'Identificación', 'field' => 'usuario.identificacion'])
            ->add('nombreCompleto', TextColumn::class, ['label' => 'Nombre'])
            ->setTransformer(function ($row, Hv $hv) {
                $row['nombreCompleto'] = $hv->getUsuario()->getNombreCompleto(false, true);
                return $row;
            })
            ->createAdapter(ORMAdapter::class, [
                'entity' => Hv::class,
                'query' => function (QueryBuilder $builder) {
                    $builder
                        ->select('hv')
                        ->from(Hv::class, 'hv')
                        ->join('hv.usuario', 'usuario');
                },
            ]);
    }
}