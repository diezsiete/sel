<?php


namespace App\Command\Helpers;


use App\Command\Helpers\ModifyRun\Annotation\ModifyRunBefore;
use App\Command\Helpers\ModifyRun\Event\ModifyRunBeforeEvent;
use App\Entity\Convenio;
use App\Entity\Empleado;
use App\Repository\ConvenioRepository;
use App\Repository\EmpleadoRepository;
use Symfony\Component\Console\Input\InputArgument;

trait SearchByConvenioOrIdent
{

    protected $searchName;
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

    protected function addSearchByConvenioOrIdent($name = 'search')
    {
        $this->searchName = $name;
        $this->addArgument($this->searchName, InputArgument::IS_ARRAY | InputArgument::OPTIONAL,
            'codigos convenios o identificaciones. Omita y se toman todos los convenios');
        return $this;
    }

    /**
     * @ModifyRunBefore
     */
    public function assignSearchValue(ModifyRunBeforeEvent $event)
    {
        if($this->searchName) {
            $this->searchValue = $event->input->getArgument($this->searchName);
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
     * @return Empleado[]
     */
    protected function getEmpleados()
    {
        if (!$this->isSearchConvenio()) {
            return $this->empleadoRepository->findByIdentificacion($this->searchValue);
        } else {
            return $this->empleadoRepository->findByConvenio($this->searchValue);
        }
    }

    /**
     * Obliga a utilizar el trait ModifyRun
     */
    abstract protected function modifyRun();
}