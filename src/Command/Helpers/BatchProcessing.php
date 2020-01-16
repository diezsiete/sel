<?php


namespace App\Command\Helpers;


trait BatchProcessing
{
    protected $batchSize = 20;
    private $batchCounter = 0;
    private $batchEntities = [];

    protected function batch($elements)
    {
        foreach($elements as $element) {
            yield $element;
            if (($this->batchCounter % $this->batchSize) === 0) {
                $this->emFlushAndClear();
            }
            //TODO el command puede no utilizar trait ConsoleProgressBar
            $this->progressBarAdvance();
            $this->batchCounter++;
        }

        $this->emFlushAndClear();
    }

    protected function emFlushAndClear()
    {
        //TODO el command puede no utilizar la opcion info
        if(!$this->input->getOption('info')) {
            //TODO el command puede no utilizar trait SelCommandTrait que trae em (entityManagerInterface)
            $this->em->flush();
            $this->em->clear();
            $this->batchEntities = [];
        }
    }

    protected function setBatchEntity($entityName, $entity = null)
    {
        $this->batchEntities[$entityName] = $entity;
        return $this;
    }

    protected function getBatchEntity($entityName)
    {
        return $this->batchEntities[$entityName] ?? false;
    }

}