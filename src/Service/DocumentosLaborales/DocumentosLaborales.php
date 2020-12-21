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
     * @param bool $public
     * @return DocumentoLaboral[]|DocumentoLaboral
     */
    public function get($searchKey = null, $public = true)
    {
        $this->initDocumentosLaborales();
        if($searchKey) {
            $docLaboral = $this->documentosLaborales[$searchKey] ?? null;
            if($docLaboral && ($public && $docLaboral->isPublic()) || (!$public && !$docLaboral->isPublic())) {
                return $docLaboral;
            }
            throw new \RuntimeException("documento laboral '$searchKey' doesn't exists'");
        }
        return array_filter($this->documentosLaborales, function(DocumentoLaboral $documentoLaboral) use ($public) {
            return $public ? $documentoLaboral->isPublic() : !$documentoLaboral->isPublic();
        });
    }

    public function getByCategory($category, $public = true)
    {
        $this->initDocumentosLaborales();
        $documentos = [];
        foreach($this->documentosLaborales as $documentoLaboral) {
            if($documentoLaboral->getCategory() === $category && (($public && $documentoLaboral->isPublic()) || (!$public && !$documentoLaboral->isPublic()))) {
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