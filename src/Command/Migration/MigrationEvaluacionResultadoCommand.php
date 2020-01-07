<?php


namespace App\Command\Migration;


use App\Command\Helpers\SelCommandTrait;
use App\Entity\Evaluacion\Evaluacion;
use App\Entity\Evaluacion\Modulo;
use App\Entity\Evaluacion\Pregunta\MultipleOrdenar;
use App\Entity\Evaluacion\Pregunta\MultipleUnica;
use App\Entity\Evaluacion\Respuesta\MultipleUnica as RespuestaMultipleUnica;
use App\Entity\Evaluacion\Respuesta\MultipleOrdenar as RespuestaMultipleOrdenar;
use App\Entity\Evaluacion\Pregunta\Pregunta;
use App\Entity\Evaluacion\Progreso;
use App\Entity\Evaluacion\Respuesta\Opcion;
use App\Entity\Evaluacion\Respuesta\Respuesta;
use App\Entity\Main\Usuario;
use DateTime;
use Exception;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class MigrationEvaluacionResultadoCommand extends MigrationCommand
{
    use SelCommandTrait;

    public static $defaultName = "sel:migration:evaluacion-resultado";

    /**
     * @var Usuario|null|false
     */
    private $currentUser = false;

    /**
     * @var Evaluacion
     */
    private $currentEvaluacion = null;

    /**
     * @var MultipleOrdenar|MultipleUnica
     */
    private $currentPregunta = null;

    /**
     * @var Progreso
     */
    private $currentProgreso = null;

    private $mapPreguntas = [];

    private $mapOpcionRespuesta = [];
    
    protected function configure()
    {
        parent::configure();
        $this->setDescription("Migrar resultados evaluacion")
            ->addOption('uid', null, InputOption::VALUE_OPTIONAL,
                'Importar solo uno por id de usuario');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $uid = $input->getOption('uid');
        $sql = "SELECT * FROM evaluacion_resultado";
        if($uid) {
            $sql .= " WHERE usuario_id = $uid";
        }
        $sqlResultado = $this->addLimitToSql($sql);

        $sqlRespuesta = "SELECT * FROM evaluacion_respuesta er
                         JOIN evaluacion_pregunta ep ON ep.id = er.pregunta_id";

        if($uid) {
            $sqlRespuesta .= " JOIN evaluacion_resultado r ON er.resultado_id = r.id WHERE r.usuario_id = $uid";
        }

        $count = $this->countSql([$sqlResultado, $sqlRespuesta]);

        $this->initProgressBar($count);
        
        while($row = $this->fetch($sqlResultado)) {
            if($usuario = $this->getUsuario($row['usuario_id'])) {
                if($progresoObject = $this->instanceProgreso($usuario, $row)) {
                    $this->selPersist($progresoObject);
                    $sqlRespuestaFiltered = $sqlRespuesta . ($uid ? " AND" : " WHERE") . " er.resultado_id = " . $row['id'];
                    while ($rowRespuesta = $this->fetch($sqlRespuestaFiltered)) {
                        $pregunta = $this->findPregunta($rowRespuesta);
                        $progreso = $this->findProgreso($progresoObject->getId());
                        $respuesta = $this->instanceRespuesta($progreso, $pregunta, $rowRespuesta);
                        $this->selPersist($respuesta);
                    }
                }
            }
        }
    }

    protected function down(InputInterface $input, OutputInterface $output)
    {
        $this->truncateTable(Opcion::class);
        $this->truncateTable(Respuesta::class);
        $this->truncateTable(Progreso::class);
    }

    /**
     * @param Usuario $usuario
     * @param array $rowResultado
     * @return Progreso|null
     */
    private function instanceProgreso(Usuario $usuario, $rowResultado)
    {
        $progreso = null;
        $culminacion = $rowResultado['culminacion'];
        if($culminacion) {
            $progreso = (new Progreso())
                ->setUsuario($usuario)
                ->setEvaluacion($this->getEvaluacion())
                ->setCulminacion($culminacion ? DateTime::createFromFormat('Y-m-d H:i:s', $culminacion) : null)
                ->setPorcentajeCompletitud($rowResultado['porcentaje_completitud'])
                ->setPorcentajeExito($rowResultado['porcentaje_exito'])
                ->setDescripcion($rowResultado['descripcion'])
                ->setPreguntasEnabled(false);
        }
        return $progreso;
    }

    /**
     * @param $rowPregunta
     * @return Pregunta|object|null
     * @throws Exception
     */
    private function findPregunta($rowPregunta)
    {
        $preguntaRepo = $this->em->getRepository(Pregunta::class);
        if(!isset($this->mapPreguntas[$rowPregunta['pregunta_id']])) {
            $pregunta = $preguntaRepo->findOneBy(['texto' => $rowPregunta['texto']]);
            if(!$pregunta) {
                throw new Exception("Pregunta '{$rowPregunta['texto']}' not found");
            }
            $this->mapPreguntas[$rowPregunta['pregunta_id']] = $pregunta->getId();
        }
        $preguntaId = $this->mapPreguntas[$rowPregunta['pregunta_id']];
        if(!$this->currentPregunta || $this->currentPregunta->getId() !== $preguntaId) {
            $this->currentPregunta = $preguntaRepo->find($preguntaId);
            if(!isset($this->mapOpcionRespuesta[$this->currentPregunta->getId()])) {
                $this->mapOpcionRespuesta[$this->currentPregunta->getId()] = $this->currentPregunta->getOpcionRespuesta();
            }
        }
        return $this->currentPregunta;
    }

    /**
     * @param $idOld
     * @return Usuario|null
     */
    private function getUsuario($idOld)
    {
        if(!$this->currentUser || $this->currentUser->getIdOld() !== $idOld) {
            $this->currentUser = $this->getUsuarioByIdOld($idOld);
        }
        return $this->currentUser;
    }

    /**
     * @return Evaluacion
     */
    private function getEvaluacion()
    {
        if(!$this->currentEvaluacion) {
            $this->currentEvaluacion = $this->em->getRepository(Evaluacion::class)->findOneBy(['slug' => 'induccion']);
        }
        return $this->currentEvaluacion;
    }

    /**
     * @param $id
     * @return Progreso
     */
    private function findProgreso($id)
    {
        if(!$this->currentProgreso || $this->currentProgreso->getId() !== $id) {
            $this->currentProgreso = $this->em->getRepository(Progreso::class)->find($id);
        }
        return $this->currentProgreso;
    }

    protected function flushAndClear()
    {
        parent::flushAndClear();
        $this->currentUser = false;
        $this->currentEvaluacion = null;
        $this->currentPregunta = null;
        $this->currentProgreso = null;
        $this->mapOpcionRespuesta = [];
    }

    private function instanceRespuesta(Progreso $progreso, Pregunta $pregunta, $rowRespuesta)
    {
        $respuesta = $pregunta instanceof MultipleOrdenar ? new RespuestaMultipleOrdenar() : new RespuestaMultipleUnica();
        $respuesta
            ->setProgreso($progreso)
            ->setPregunta($pregunta)
            ->setRespondidaEn(DateTime::createFromFormat('Y-m-d H:i:s', $rowRespuesta['respondida_en']));
        if($pregunta instanceof MultipleOrdenar) {
            foreach($this->mapOpcionRespuesta[$pregunta->getId()] as $opcion) {
                $respuesta->addOpcion((new Opcion())->setPreguntaOpcion($opcion));
            }
        } else {
            $respuesta->addOpcion((new Opcion())->setPreguntaOpcion($this->mapOpcionRespuesta[$pregunta->getId()]));
        }
        return $respuesta;
    }
}