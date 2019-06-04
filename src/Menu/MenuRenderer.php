<?php


namespace App\Menu;


use Knp\Menu\ItemInterface;
use Knp\Menu\Renderer\ListRenderer;
use Knp\Menu\Renderer\RendererInterface;

class MenuRenderer extends ListRenderer
{

    public function render(ItemInterface $item, array $options = array())
    {
        return parent::render($item, $options);
    }

    protected function renderLabel(ItemInterface $item, array $options)
    {
        $html = parent::renderLabel($item, $options);
        if($icon = $item->getExtra('icon')) {
            $html = '<i class="'.$icon.'" aria-hidden="true"></i><span>'.$html.'</span>';
        }
        return $html;
    }

    protected function renderLinkElement(ItemInterface $item, array $options)
    {
        $item->setLinkAttribute('class', 'nav-link');
        return parent::renderLinkElement($item, $options);
    }

    protected function renderList(ItemInterface $item, array $attributes, array $options)
    {
        if ($item->hasChildren()) {
            $rootClass = $options['rootClass'] ?? '';
            $attributes['class'] = $item->isRoot() ? $rootClass : 'nav nav-children';
        }
        return parent::renderList($item, $attributes, $options);
    }
}