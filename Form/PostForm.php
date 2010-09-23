<?php

namespace Bundle\Balibali\BlogBundle\Form;

use Symfony\Component\Form\Form;
use Symfony\Component\Form\TextField;
use Symfony\Component\Form\TextareaField;
use Symfony\Component\Validator\ValidatorInterface;
use Bundle\Balibali\BlogBundle\Document\Post;

class PostForm extends Form
{
    public function __construct($name, Post $object, ValidatorInterface $validator, array $options = array())
    {
        parent::__construct($name, $object, $validator, $options);

        $this->add(new TextField('title'));
        $this->add(new TextareaField('body'));
    }
}
