<?php


namespace App\Command\Migration;


use App\Command\Helpers\SelCommandTrait;
use App\Entity\Evaluacion\Diapositiva;
use App\Entity\Evaluacion\Evaluacion;
use App\Entity\Evaluacion\Modulo;
use App\Entity\Evaluacion\Pregunta\MultipleOrdenar;
use App\Entity\Evaluacion\Pregunta\MultipleUnica;
use App\Entity\Evaluacion\Pregunta\Opcion;
use App\Entity\Evaluacion\Pregunta\Pregunta;
use App\Entity\Evaluacion\Presentacion;
use DateTime;
use Doctrine\DBAL\DBALException;
use Doctrine\ORM\NonUniqueResultException as NonUniqueResultExceptionAlias;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class MigrationEvaluacionCommand extends MigrationCommand
{
    use SelCommandTrait;

    protected static $defaultName = "sel:migration:evaluacion";

    /**
     * @var Presentacion
     */
    private $currentPresentacion = null;

    /**
     * @var Evaluacion
     */
    private $currentEvaluacion = null;

    /**
     * @var Modulo
     */
    private $currentModulo = null;

    private $entitiesCached = [];


    protected function configure()
    {
        parent::configure();
        $this->setDescription("Migrar evaluaciones (sin respuestas)");
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $sqlPresentacion = "SELECT * FROM evaluacion_presentacion";
        $sqlDiapositiva = "SELECT * FROM evaluacion_presentacion_diapositiva";

        $sqlEvaluacion = "SELECT * FROM evaluacion";
        $sqlModulo = "SELECT * FROM evaluacion_modulo";

        $sqlPregunta = "SELECT p.*, w.tipo, w.mensaje_ayuda, wm.id wmid
                        FROM evaluacion_pregunta p
                        JOIN evaluacion_widget w ON w.pregunta_id = p.id
                        JOIN evaluacion_widget_multiple wm ON wm.widget_id = w.id";

        $sqlOpcion = "SELECT * FROM evaluacion_widget_multiple_opcion";

        $count = $this->countSql([$sqlPresentacion, $sqlDiapositiva, $sqlEvaluacion, $sqlModulo, $sqlPregunta, $sqlOpcion]);

        $this->initProgressBar($count);

        while($rowPresentacion = $this->fetch($sqlPresentacion)) {
            $object = $this->instancePresentacion($rowPresentacion);
            $this->selPersist($object);
            while($rowDiapositiva = $this->fetch($sqlDiapositiva)) {
                $presentacion = $this->findEntity(Presentacion::class, $object->getId());
                $diapositiva = $this->instanceDiapositiva($rowDiapositiva, $presentacion);
                $this->selPersist($diapositiva);
            }
        }

        while($rowEvaluacion = $this->fetch($sqlEvaluacion)) {
            $object = $this->instanceEvaluacion($rowEvaluacion);
            $this->selPersist($object);
            while($rowModulo = $this->fetch($sqlModulo)) {
                $objectModulo = $this->instanceModulo($rowModulo, $this->findEntity(Evaluacion::class, $object->getId()));

                $diapositivas = $this->instanceDiapositivasByRango($rowModulo['presentacion_vinculada_id']);
                foreach($diapositivas as $diapositiva) {
                    $objectModulo->addDiapositiva($diapositiva);
                }

                $this->selPersist($objectModulo);
                $sqlPreguntaFiltered = $sqlPregunta . " WHERE p.evaluacion_id = {$rowEvaluacion['id']} AND p.modulo_id = {$rowModulo['id']}";
                while($rowPregunta = $this->fetch($sqlPreguntaFiltered)) {
                    $objectPregunta = $this->instancePregunta($rowPregunta);

                    $sqlOpcionFiltered = $sqlOpcion . " WHERE widget_multiple_id = " . $rowPregunta['wmid'];
                    while($rowOpcion = $this->fetch($sqlOpcionFiltered)) {
                        $opcionObject = $this->instanceOpcion($objectPregunta, $rowOpcion);
                        $objectPregunta->addOpcion($opcionObject);
                        $this->progressBar->advance();
                    }

                    $diapositivas = $this->instanceDiapositivasByRango($rowPregunta['presentacion_vinculada_id']);
                    foreach($diapositivas as $diapositiva) {
                        $objectPregunta->addDiapositiva($diapositiva);
                    }

                    $this->selPersist($objectPregunta
                        ->setEvaluacion($this->findEntity(Evaluacion::class, $object->getId()))
                        ->setModulo($this->findEntity(Modulo::class, $objectModulo->getId()))
                    );
                }
            }
        }
    }

    /**
     * @param $data
     * @return Presentacion
     * @throws NonUniqueResultExceptionAlias
     */
    private function instancePresentacion($data)
    {
        $presentacion = (new Presentacion())
            ->setNombre($data['nombre'])
            ->setSlug($data['llave'])
            ->setUsuario($this->getSuperAdmin())
            ->setCreatedAt(DateTime::createFromFormat('Y-m-d H:i:s', $data['creacion']));
        return $presentacion;
    }

    private function instanceDiapositiva($data, Presentacion $presentacion)
    {
        $diapositiva = (new Diapositiva())
            ->setPresentacion($presentacion)
            ->setIndice((int)$data['indice'])
            ->setNombre($data['nombre']);
        return $diapositiva;
    }

    private function instanceEvaluacion($data)
    {
        return (new Evaluacion())
            ->setNombre($data['nombre'])
            ->setSlug($data['llave'])
            ->setUsuario($this->getSuperAdmin())
            ->setCreatedAt(DateTime::createFromFormat('Y-m-d H:i:s', $data['creacion']))
            ->setMinimoPorcentajeExito($data['minimo_porcentaje_exito'])
            ->setRepetirEnFallo((bool)$data['accion_fallo'])
            ->setGuardarProceso((bool)$data['guardar_en_proceso']);
    }

    private function instanceModulo($data, Evaluacion $evaluacion)
    {
        return (new Modulo())
            ->setEvaluacion($evaluacion)
            ->setNombre($data['nombre'])
            ->setIndice($data['indice'])
            ->setNumeroIntentos($data['numero_intentos'])
            ->setRepetirEnFallo((bool)$data['accion_fallo']);
            
    }

    protected function down(InputInterface $input, OutputInterface $output)
    {
        $this->truncateTable(Presentacion::class);
        $this->truncateTable(Diapositiva::class);


        $this->truncateTable(Opcion::class);
        foreach($this->em->getRepository(Pregunta::class)->findAll() as $pregunta) {
            $this->em->remove($pregunta);
        }
        $this->em->flush();
        $this->truncateTable(Pregunta::class);


        foreach($this->em->getRepository(Modulo::class)->findAll() as $modulo) {
            $this->em->remove($modulo);
        }
        $this->em->flush();
        $this->truncateTable(Modulo::class);

        $this->truncateTable(Evaluacion::class);
    }

    protected function flushAndClear()
    {
        parent::flushAndClear();
        $this->superAdmin = null;
        $this->entitiesCached = [];
    }

    /**
     * @param $entityClass
     * @param $id
     * @return mixed
     */
    private function findEntity($entityClass, $id)
    {
        if(!isset($this->entitiesCached[$entityClass]) || $this->entitiesCached[$entityClass]->getId() !== $id) {
            $this->entitiesCached[$entityClass] = $this->em->getRepository($entityClass)->find($id);
        }
        return $this->entitiesCached[$entityClass];
    }

    /**
     * @param $presentacionVinculadaId
     * @return Diapositiva|Diapositiva[]
     * @throws DBALException
     */
    private function instanceDiapositivasByRango($presentacionVinculadaId)
    {
        $diapositivas = [];
        if($presentacionVinculadaId) {
            $rangoExplode = explode(':',
                $this->getConnection()->fetchColumn(
                    "SELECT rango_diapositivas 
                           FROM evaluacion_presentacion_vinculada 
                           WHERE id = $presentacionVinculadaId")
            );

            $rangoInit = (int)$rangoExplode[0];
            $rangoFin = (int)$rangoExplode[1];
            $rango = [$rangoInit];
            for ($i = $rangoInit + 1; $i <= $rangoFin; $i++) {
                $rango[] = $i;
            }
            $diapositivas = $this->em->getRepository(Diapositiva::class)->findByIndice($rango);
        }
        return $diapositivas;
    }

    /**
     * @param $rowPregunta
     * @return MultipleOrdenar|MultipleUnica
     */
    private function instancePregunta($rowPregunta)
    {
        $tipo = $rowPregunta['tipo'];
        $pregunta = $tipo === 'Multiple' ? new MultipleUnica() : new MultipleOrdenar();

        $pregunta
            ->setTexto($rowPregunta['texto'])
            ->setIndice((int)$rowPregunta['indice'])
            ->setPorcentajeExito((int)$rowPregunta['porcentaje_exito'])
            ->setNumeroIntentos((int)$rowPregunta['numero_intentos']);

        return $pregunta;
    }

    /**
     * @param Pregunta $pregunta
     * @param $rowOpcion
     * @return Opcion
     * @throws DBALException
     */
    private function instanceOpcion(Pregunta $pregunta, $rowOpcion)
    {
        $opcion = (new Opcion())
            ->setPregunta($pregunta)
            ->setTexto($rowOpcion['texto']);

        $tableRespuesta = "evaluacion_widget_multiple_respuesta";
        if($pregunta instanceof MultipleOrdenar) {
            $respuestas = $this->getConnection()->fetchAll(
                "SELECT * FROM $tableRespuesta WHERE widget_multiple_id = " . $rowOpcion['widget_multiple_id'] . " ORDER BY id ASC");
            for($i = 0; $i < count($respuestas); $i++){
                if($respuestas[$i]['widget_multiple_opcion_id'] == $rowOpcion['id']) {
                    $opcion->setRespuesta($i + 1);
                }
            }
        } else {
            $respuestaId = $this->getConnection()->fetchColumn(
                "SELECT id FROM $tableRespuesta WHERE widget_multiple_opcion_id = " . $rowOpcion['id']);
            $opcion->setRespuesta($respuestaId ? 1 : 0);
        }
        return $opcion;
    }


}