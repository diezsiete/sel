<?php


namespace App\Command\Migration;


use App\Command\Helpers\SelCommandTrait;
use App\Entity\Convenio;
use App\Entity\Empleado;
use App\Entity\Representante;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class MigrationRepresentanteCommand extends MigrationCommand
{
    use SelCommandTrait;

    protected static $defaultName = "sel:migration:representante";

    /**
     * @var Representante
     */
    private $currentRepresentante = null;

    protected function configure()
    {
        parent::configure();
        $this->setDescription("Migrar representantes");
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $sqlRepresentante = $this->addLimitToSql("SELECT * FROM clientes_usuario");
        $count = $this->countSql($sqlRepresentante);

        $sqlAsignado =
            "SELECT cra.*, e.identificacion, e.email
             FROM clientes_usuario cu 
                JOIN clientes_rep_asignado cra ON cra.clientes_usuario_id = cu.id
                JOIN novasoft_empleado e ON e.id = cra.novasoft_empleado_id";
        $count += $this->countSql($this->addLimitToSql($sqlAsignado));

        $this->initProgressBar($count);

        while($representanteRow = $this->fetch($sqlRepresentante)) {
            $usuario = $this->getUsuarioByIdOld($representanteRow['usuario_id']);
            $convenio = $this->em->getRepository(Convenio::class)->find($representanteRow['novasoft_convenio_codigo']);
            if($usuario && $convenio) {
                $representante = (new Representante())
                    ->setUsuario($usuario)
                    ->setConvenio($convenio)
                    ->setEmail($usuario->getEmail())
                    ->setBcc((bool)$representanteRow['bcc'])
                    ->setEncargado((bool)$representanteRow['encargado']);
                $this->selPersist($representante);

                while($rowAsignado = $this->fetch($sqlAsignado . " WHERE cra.clientes_usuario_id = " . $representanteRow['id'])) {
                    $representante = $this->findRepresentante($representante->getId());
                    if($empleado = $this->findEmpleado($rowAsignado)) {
                        $empleado->setRepresentante($representante);
                        $this->progressBar->advance();
                    }
                }

            } else {
                if(!$convenio) {
                    $this->io->error("el convenio '" . $representanteRow['novasoft_convenio_codigo'] . "' no existe");
                }
            }
        }
    }

    protected function down(InputInterface $input, OutputInterface $output)
    {
        $q = $this->em->createQuery('update ' . Empleado::class . ' e set e.representante = null');
        $q->execute();
        $this->truncateTable(Representante::class);
    }

    /**
     * @param $id
     * @return Representante
     */
    private function findRepresentante($id)
    {
        if(!$this->currentRepresentante || $this->currentRepresentante->getId() !== $id) {
            $this->currentRepresentante = $this->em->getRepository(Representante::class)->find($id);
        }
        return $this->currentRepresentante;
    }

    private function findEmpleado($row)
    {
        $empleadoRepo = $this->em->getRepository(Empleado::class);
        $empleado = $empleadoRepo->findByIdentificacion($row['identificacion']);
        if(!$empleado) {
            // try search by email
            $this->io->warning("empleado '" . $row['identificacion'] . "' not found, searching by email '" . $row['email'] . "'");
            $empleado = $empleadoRepo->findByEmail($row['email']);
            if($empleado) {
                $this->io->success("found");
            } else {
                $this->io->error("not found");
            }
        }
        return $empleado;
    }

    protected function flushAndClear()
    {
        parent::flushAndClear();
        $this->currentRepresentante = null;
    }
}