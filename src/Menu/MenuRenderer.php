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
        $this->addAttributesToItem($item, 'link', $options);

        return parent::renderLinkElement($item, $options);
    }

    protected function renderList(ItemInterface $item, array $attributes, array $options)
    {
        if ($item->hasChildren()) {
            if($item->isRoot()) {
                $attributes += $this->addAttributesToItem($item, 'root', $options, $attributes);
            } else {
                $attributes = array_merge($attributes, $this->addAttributesToItem($item, 'list', $options, $attributes));
            }
        }
        return parent::renderList($item, $attributes, $options);
    }

    private function addAttributesToItem(ItemInterface $item, $itemName, array $options, $attributes = null)
    {
        if(isset($options[$itemName]) && isset($options[$itemName]['attributes'])) {
            foreach($options[$itemName]['attributes'] as $attributeName => $attributeValue) {

                if(is_array($attributeValue)) {
                    $condition = $attributeValue;
                    $conditionMethod = array_key_first($condition);

                    if(is_array($condition[$conditionMethod])) {
                        $newValue = null;
                        foreach ($condition[$conditionMethod] as $met => $value) {
                            if($item->$conditionMethod() == $met) {
                                $newValue = $value;
                            }
                        }
                        if($newValue === null) {
                            continue;
                        } else {
                            $attributeValue = $newValue;
                        }
                    } else {
                        if ($item->$conditionMethod() != $condition[$conditionMethod]) {
                            continue;
                        } else {
                            $attributeValue = $condition['value'];
                        }
                    }
                }

                if(is_array($attributes)) {
                    $attributes[$attributeName] = $attributeValue;
                } else {
                    $item->setLinkAttribute($attributeName, $attributeValue);
                }
            }
        }

        return is_array($attributes) ? $attributes : $item;
    }
}