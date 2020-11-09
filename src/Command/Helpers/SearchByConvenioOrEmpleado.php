<?php


namespace App\Command\Helpers;


use App\Command\Helpers\TraitableCommand\Annotation\BeforeRun;
use App\Command\Helpers\TraitableCommand\Annotation\Configure;
use App\Command\Helpers\TraitableCommand\Event\BeforeRunEvent;
use App\Entity\Main\Convenio;
use App\Entity\Main\Empleado;
use App\Repository\Main\ConvenioRepository;
use App\Repository\Main\EmpleadoRepository;
use Doctrine\ORM\Query\QueryException;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

trait SearchByConvenioOrEmpleado
{

    protected $searchName = 'search';
    protected $searchValue = [];
    protected $activo = null;

    protected $disableSearchEmpleado = false;

    /**
     * @var ConvenioRepository
     */
    protected $convenioRepository;

    /**
     * @var EmpleadoRepository
     */
    protected $empleadoRepository;

    /**
     * @required
     */
    public function setConvenioReporistory(ConvenioRepository $convenioRepository)
    {
        $this->convenioRepository = $convenioRepository;
    }

    /**
     * @required
     */
    public function setEmpleadoRepository(EmpleadoRepository $empleadoRepository)
    {
        $this->empleadoRepository = $empleadoRepository;
    }


    /**
     * @Configure
     */
    public function addSearchByConvenioOrIdent()
    {
        $description = 'codigos convenios' . ($this->disableSearchEmpleado ? '' : ' o identificaciones')
            . '. Omita y se toman todos los convenios';
        $this->addArgument($this->searchName, InputArgument::IS_ARRAY | InputArgument::OPTIONAL, $description);
        $this->addOption('activo', null, InputOption::VALUE_NONE,
                'Selecciona empleados activos. Cuya fecha de retiro sea null o mayor a la actual');
        return $this;
    }

    /**
     * @BeforeRun
     */
    public function assignSearchValue(BeforeRunEvent $event)
    {
        if($this->searchName) {
            $this->searchValue = $event->getInput()->getArgument($this->searchName);
        }
        $this->activo = $event->getInput()->getOption('activo');
    }


    protected function isSearchConvenio(): bool
    {
        if($this->disableSearchEmpleado) {
            return true;
        }
        return $this->searchValue ? !is_numeric($this->searchValue[0]) : true;
    }


    protected function isSearchConvenioAll(): bool
    {
        return $this->isSearchConvenio() && !$this->searchValue;
    }

    /**
     * @return string[]
     */
    protected function getConveniosCodigos()
    {
        $conveniosCodigos = [];
        if($this->isSearchConvenio()) {
            if($this->searchValue) {
                if(count($this->searchValue) === 1 && preg_match('/(\*|%)/', $this->searchValue[0], $matches)) {

                    $conveniosCodigos = array_map(function (Convenio $convenio) {
                        return $convenio->getCodigo();
                    }, $this->convenioRepository->findByCodigo($this->searchValue[0]));
                } else {
                    $conveniosCodigos = $this->searchValue;
                }
            } else {
                $conveniosCodigos = $this->convenioRepository->findAllCodigos();
            }
        }
        return $conveniosCodigos;
    }

    /**
     * @return Convenio[]
     */
    protected function getConvenios()
    {
        $convenios = [];
        if($this->isSearchConvenio()) {
            $convenios = $this->searchValue ?
                $this->convenioRepository->findByCodigo($this->searchValue) :
                $this->convenioRepository->findAll();
        }
        return $convenios;
    }

    protected function getIdents()
    {
        return !$this->isSearchConvenio() ? $this->searchValue : [];
    }

    /**
     * @return Empleado|Empleado[]|null
     * @throws QueryException
     */
    protected function getEmpleados($field = null)
    {
        if (!$this->isSearchConvenio()) {
            return $this->empleadoRepository->findByIdentificacion($this->searchValue);
        } else {
            return $this->empleadoRepository->findByConvenio($this->searchValue, $this->activo, $field);
        }
    }


    protected function execute(InputInterface $input, OutputInterface $output)
    {
        if($this->isSearchConvenio()) {
            $this->executeConvenios($this->getConveniosCodigos());
        } else {
            $this->executeEmpleados($this->getEmpleados());
        }
    }

    /**
     * @param string[] $conveniosCodigos
     * @return mixed
     */
    protected function executeConvenios($conveniosCodigos)
    {

    }

    /**
     * @param Empleado|Empleado[]|null
     * @return mixed
     */
    protected function executeEmpleados($empleados)
    {

    }
}