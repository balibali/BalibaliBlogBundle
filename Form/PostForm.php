<?php

namespace Balibali\BlogBundle\Form;

use Symfony\Component\Form\Form;
use Symfony\Component\Form\TextField;
use Symfony\Component\Form\TextareaField;
use Symfony\Component\Form\ChoiceField;
use Symfony\Component\Validator\ValidatorInterface;
use Balibali\BlogBundle\Document\Post;

class PostForm extends Form
{
    protected function configure()
    {
        $this->addOption('use_format', true);

        $this->add(new TextField('title'));
        $this->add(new TextField('slug'));
        $this->add(new TextareaField('body'));

        if ($this->getOption('use_format')) {
            $formats = array(
                'markdown' => 'Markdown',
                'html'     => 'HTML',
            );

            $this->add(new ChoiceField('format', array('choices' => $formats)));
        }
    }
}
