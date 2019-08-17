<?php


namespace App\Command\Helpers;


use App\Command\Helpers\TraitableCommand\Annotation\BeforeRun;
use App\Command\Helpers\TraitableCommand\Annotation\Configure;
use App\Command\Helpers\TraitableCommand\Event\BeforeRunEvent;
use App\Entity\Convenio;
use App\Entity\Empleado;
use App\Repository\ConvenioRepository;
use App\Repository\EmpleadoRepository;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

trait SearchByConvenioOrEmpleado
{

    protected $searchName = 'search';
    protected $searchValue = [];

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
        $this->addArgument($this->searchName, InputArgument::IS_ARRAY | InputArgument::OPTIONAL,
            'codigos convenios o identificaciones. Omita y se toman todos los convenios');
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
    }


    protected function isSearchConvenio(): bool
    {
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
            $conveniosCodigos = $this->searchValue ? $this->searchValue : $this->convenioRepository->findAllCodigos();
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
                $this->convenioRepository->findBy(['codigo' => $this->searchValue]) :
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
     */
    protected function getEmpleados()
    {
        if (!$this->isSearchConvenio()) {
            return $this->empleadoRepository->findByIdentificacion($this->searchValue);
        } else {
            return $this->empleadoRepository->findByConvenio($this->searchValue);
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