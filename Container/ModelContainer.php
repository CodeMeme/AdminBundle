<?php

namespace CodeMeme\AdminBundle\Container;

use CodeMeme\AdminBundle\Form\ModelFormType;
use Symfony\Component\DependencyInjection\ContainerAware;

class ModelContainer extends ContainerAware
{

    private $class;

    private $entityManager;

    private $group;

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

    public function getGroup()
    {
        return $this->group;
    }

    public function setGroup($group)
    {
        $this->group = $group;
        
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

    public function getEntity($id = null)
    {
        if ($id && ($entity = $this->getRepository()->find($id))) {
            return $entity;
        } else {
            $class = $this->getClass();
            return new $class;
        }
    }

    public function getRepository()
    {
        return $this->getEntityManager()->getRepository($this->getClass());
    }

    public function getForm($entity)
    {
        $type = new ModelFormType($entity, $this->getEntityManager());
        
        $form = $this->container->get('form.factory')->create($type, 'entity');
        $form->setData($entity);
        
        return $form;
    }

    public function getTotal()
    {
        $qb = $this->getRepository()->createQueryBuilder('e');
        $qb->select('COUNT(e.id) AS total');
        
        $result = current($qb->getQuery()->execute());
        
        return $result['total'];
    }

    public function __toString()
    {
        return (String) $this->getLabel();
    }

}