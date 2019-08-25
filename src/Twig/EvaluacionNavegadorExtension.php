<?php


namespace App\Twig;

use App\Service\Evaluacion\Navegador;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class EvaluacionNavegadorExtension extends AbstractExtension
{

    const LINK_PREV = 'prev';
    const LINK_NEXT = 'next';

    public function getFunctions()
    {
        return array(
            new TwigFunction('sel_evaluacion_navegador_prev', [$this, 'prev'], ['is_safe' => ['html']]),
            new TwigFunction('sel_evaluacion_navegador_next', [$this, 'next'], ['is_safe' => ['html']]),
        );
    }

    public function prev(Navegador $navegador, array $attributes = [])
    {
        return $this->getLink($navegador, static::LINK_PREV, $attributes);
    }

    public function next(Navegador $navegador, array $attributes = [])
    {
        if($navegador->esPregunta()) {
            // TODO
            return "";
        } else {
            return $this->getLink($navegador, static::LINK_NEXT, $attributes);
        }
    }

    private function getLink(Navegador $navegador, $type, $attributes)
    {
        $hasRouteMethod = "has" . ucfirst($type) . "Route";
        $getRouteMethod = "get" . ucfirst($type) . "Route";
        if ($navegador->$hasRouteMethod() && $href = $navegador->$getRouteMethod()) {
            $tag = 'a';
            $attributes['href'] = $href;
        } else {
            $tag = 'span';
            !isset($attributes['class']) ? $attributes['class'] = "" : $attributes['class'] .= " ";
            $attributes['class'] .= "disabled";
        }

        $parsedAttributes = $this->parseAttributes($attributes);
        return '<'.$tag.$parsedAttributes.'>'.($type === static::LINK_PREV ? 'Anterior' : 'Siguiente').'</'.$tag.'>';
    }

    private function parseAttributes($attributes)
    {
        $parse = " ";
        foreach($attributes as $attributeName => $attributeValue) {
            $parse .= $attributeName.'="'.$attributeValue.'" ';
        }
        return $parse;
    }

}