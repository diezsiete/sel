<?php
namespace App\Twig\Component;


use Twig\Extension\AbstractExtension;
use App\Service\Component\LoadingOverlayComponent;
use Twig\TwigFunction;

class LoadingOverlayExtension extends AbstractExtension
{
    /**
     * @var LoadingOverlayComponent
     */
    private $component;

    public function __construct(LoadingOverlayComponent $loadingOverlay)
    {
        $this->component = $loadingOverlay;
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('loading_overlay_body_class', [$this, 'bodyClass']),
            new TwigFunction('loading_overlay_body_options', [$this, 'bodyOptions'], ['is_safe' => ['html']]),
            new TwigFunction('loading_overlay', [$this, 'template'], ['is_safe' => ['html']])
        ];
    }

    public function bodyClass()
    {
        if($this->component->isEnabled()) {
            return "loading-overlay-showing";
        }
        return "";
    }

    public function bodyOptions()
    {
        if($this->component->isEnabled()) {
            return "data-loading-overlay data-loading-overlay-options='".$this->component->encodeOptions()."'";
        }
        return "";
    }

    public function template()
    {
        if($this->component->isEnabled()) {
            $this->component->clearSession();
            return
                '<div class="loading-overlay">
                    <div class="bounce-loader with-text">
                        <h4>Estamos cargando su información, por favor espere un momento</h4>
                        <div class="bounce1"></div>
                        <div class="bounce2"></div>
                        <div class="bounce3"></div>
                    </div>
                </div>';
        }
        return "";
    }
}