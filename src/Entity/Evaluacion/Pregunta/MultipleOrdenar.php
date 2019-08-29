<?php


namespace App\Entity\Evaluacion\Pregunta;

use App\Repository\Evaluacion\Pregunta\OpcionRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class MultipleOrdenar
 * @package App\Entity\Evaluacion\Pregunta
 * @ORM\Entity()
 */
class MultipleOrdenar extends MultipleUnica
{
    /**
     * @param int $orden
     * @return Opcion|null
     */
    public function getOpcionByOrden(int $orden)
    {
        $colecction = $this->opciones->matching(OpcionRepository::getByOrderCriteria($orden));
        return $colecction->count() !== 0 ? $colecction->first() : null;
    }

    public function getOpcionByIndice(int $indice)
    {
        return $this->opciones->get($indice - 1);
    }
}