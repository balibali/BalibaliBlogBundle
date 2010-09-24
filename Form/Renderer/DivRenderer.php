<?php

namespace Bundle\Balibali\BlogBundle\Form\Renderer;

use Symfony\Component\Form\Renderer\Renderer;
use Symfony\Component\Form\FieldInterface;

class DivRenderer extends Renderer
{
    /**
     * {@inheritDoc}
     */
    public function render(FieldInterface $group, array $attributes = array())
    {
        $html = "\n";

        $hiddenFields = array();
        foreach ($group as $field) {
            if ($field->isHidden()) {
                $hiddenFields[] = $field;
                continue;
            }

            $html .= '<div class="field">'."\n";
            $html .= '<div class="label"><label for="'.$field->getId().'">'.ucfirst($field->getKey()).'</label></div>'."\n";
            $html .= '<div class="body">';
            if ($field->hasErrors()) {
                $html .= $field->renderErrors()."\n";
            }
            $html .= $field->render();
            $html .= '</div>'."\n";
            $html .= '</div>'."\n";
        }

        $html .= '<div class="hiddenFields">'."\n";
        foreach ($hiddenFields as $field) {
            $html .= $field->render()."\n";
        }
        $html .= '</div>'."\n";

        return $html;
    }
}
