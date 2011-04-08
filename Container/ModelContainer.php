<?php

namespace CodeMeme\AdminBundle\Container;

use CodeMeme\AdminBundle\Form\ModelFormType;
use Symfony\Component\DependencyInjection\ContainerAware;

class ModelContainer extends ContainerAware
{

    private $class;

    private $entityManager;

    private $label;

    private $slug;

    public function getClass()
    {
        return $this->class;
    }

    public function setClass($class)
    {
        $this->class = $class;
        
        return $this;
    }

    public function getEntitymanager()
    {
        return $this->entityManager;
    }

    public function setEntitymanager($entityManager)
    {
        $this->entityManager = $entityManager;
        
        return $this;
    }

    public function getLabel()
    {
        return $this->label;
    }

    public function setLabel($label)
    {
        $this->label = $label;
        
        return $this;
    }

    public function getSlug()
    {
        return $this->slug;
    }

    public function setSlug($slug)
    {
        $this->slug = $slug;
        
        return $this;
    }

    public function getRepository()
    {
        return $this->getEntityManager()->getRepository($this->getClass());
    }

    public function getForm($entity)
    {
        $type = new ModelFormType($entity, $this->getEntityManager());
        
        $form = $this->container->get('form.factory')->create($type);
        $form->setData($entity);
        
        return $form;
    }

    public function __toString()
    {
        return $this->getLabel();
    }

}