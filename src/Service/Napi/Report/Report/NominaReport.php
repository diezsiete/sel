<?php


namespace App\Service\Napi\Report\Report;


use App\Entity\Main\Usuario;
use App\Entity\Napi\Report\ServicioEmpleados\Nomina;
use App\Service\Napi\Report\SsrsReport;

class NominaReport extends SsrsReport
{

    public function import(Usuario $usuario)
    {
        /** @var Nomina[] $nominas */
        $nominas = $this->client->collectionOperations(Nomina::class)->get(
            $usuario->getIdentificacion(), '2017-02-01', (new \DateTime())->format('Y-m-t')
        );

        if($nominas) {
            foreach($nominas as $nomina) {
                if (!$nomina->getId()) {
                    $nomina->setUsuario($usuario);

                    $this->em->persist($nomina);
                    $this->em->flush();

                    $this->dispatchImportEvent($nomina);
                } else {
                    $this->em->flush();
                }
            }
        }
    }
}