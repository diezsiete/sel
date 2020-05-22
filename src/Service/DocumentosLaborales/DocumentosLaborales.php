<?php


namespace App\Service\DocumentosLaborales;


use Exception;
use Symfony\Component\DependencyInjection\ParameterBag\ContainerBagInterface;

class DocumentosLaborales
{
    /**
     * @var array
     */
    private $parameters;
    /**
     * @var DocumentoLaboral[]
     */
    private $documentosLaborales = [];
    private $empresa;
    private $categories = [];

    public function __construct(ContainerBagInterface $bag)
    {
        $this->empresa = $bag->get('empresa');
        $this->parameters = $bag->get('empresa.'.$this->empresa.'.config');
    }

    /**
     * @param null|string $searchKey
     * @return DocumentoLaboral[]|DocumentoLaboral
     * @throws Exception
     */
    public function get($searchKey = null)
    {
        $this->initDocumentosLaborales();
        if($searchKey) {
            if(isset($this->documentosLaborales[$searchKey])) {
                return $this->documentosLaborales[$searchKey];
            }
            throw new \RuntimeException("documento laboral '$searchKey' doesn't exists'");
        }
        return $this->documentosLaborales;
    }

    public function getByCategory($category)
    {
        $this->initDocumentosLaborales();
        $documentos = [];
        foreach($this->documentosLaborales as $documentoLaboral) {
            if($documentoLaboral->getCategory() === $category) {
                $documentos[] = $documentoLaboral;
            }
        }
        return $documentos;
    }

    public function getCategories()
    {
        $this->initDocumentosLaborales();
        return $this->categories;
    }

    private function initDocumentosLaborales()
    {
        if(!$this->documentosLaborales) {
            foreach($this->parameters['documentos_laborales'] as $key => $documentoLaboralData) {
                $documentoLaboralData['key'] = $key;
                $this->documentosLaborales[$key] = new DocumentoLaboral($this->empresa, $documentoLaboralData);

                $category = $this->documentosLaborales[$key]->getCategory();
                if(!in_array($category, $this->categories, true)) {
                    $this->categories[] = $category;
                }
            }
        }
    }
}