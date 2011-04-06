<?php

namespace CodeMeme\AdminBundle\Container;

use Symfony\Component\DependencyInjection\ContainerAware;

class ModelContainer extends ContainerAware
{

    private $class;

    private $em = 'default';

    public function getClass()
    {
        return $this->class;
    }

    public function setClass($class)
    {
        $this->class = $class;
        
        return $this;
    }

    public function getEntityManager()
    {
        $em = $this->container->get(sprintf(
            'doctrine.orm.%s_entity_manager',
            $this->em
        ));
        
        return $em;
    }

}