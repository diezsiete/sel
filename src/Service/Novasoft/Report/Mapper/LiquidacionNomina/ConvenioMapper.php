<?php


namespace App\Service\Novasoft\Report\Mapper\LiquidacionNomina;


use App\Entity\Main\Convenio;
use App\Repository\Main\ConvenioRepository;
use App\Service\Novasoft\Report\Mapper\DataFilter;
use App\Service\Novasoft\Report\Mapper\Mapper;

class ConvenioMapper extends Mapper
{

    /**
     * @var \App\Repository\Main\ConvenioRepository
     */
    private $convenioRepository;

    /**
     * @var Convenio
     */
    protected $targetObject;

    public function __construct(DataFilter $filter, ConvenioRepository $convenioRepository)
    {
        parent::__construct($filter);
        $this->convenioRepository = $convenioRepository;
    }

    protected function instanceTargetObject()
    {
        return new Convenio();
    }

    protected function defineMap(): array
    {
        return [
            'textbox4' => 'codigo',
            'textbox5' => 'nombre'
        ];
    }

    public function addMappedObject(&$objects)
    {
        $databaseConvenio = $this->convenioRepository->find($this->targetObject->getCodigo());
        if($databaseConvenio) {
            $this->targetObject = $databaseConvenio;
        }
        parent::addMappedObject($objects);
    }
}