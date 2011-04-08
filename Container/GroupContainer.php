<?php

namespace CodeMeme\AdminBundle\Container;

use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\DependencyInjection\ContainerAware;

class GroupContainer extends ContainerAware
{

    private $label;

    private $models;

    public function __construct()
    {
        $this->models = new ArrayCollection;
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

    public function getModels()
    {
        return $this->models;
    }

    public function setModels($models)
    {
        $this->models = $models;
        
        return $this;
    }

    public function addModel($model)
    {
        $this->getModels()->add($model);
        
        return $this;
    }

    public function __toString()
    {
        return (String) $this->getLabel();
    }

}