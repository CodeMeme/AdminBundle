<?php

namespace CodeMeme\AdminBundle\Form;

use Doctrine\Common\Collections\Collection;

use Symfony\Component\Form\Type\AbstractType;
use Symfony\Component\Form\FormBuilder;

class ModelFormType extends AbstractType
{

    private $entity;

    private $em;

    public function __construct($entity, $em)
    {
        $this->entity   =   $entity;
        $this->em       =   $em;
    }

    public function buildForm(FormBuilder $builder, Array $options)
    {
        $metadata = $this->em->getClassMetadata(get_class($this->entity));
        
        foreach ($metadata->fieldMappings as $field => $properties) {
            $builder->add($field);
        }
        
        foreach ($metadata->associationMappings as $field => $properties) {
            $property = $metadata->reflClass->getProperty($field);
            $property->setAccessible(true);
            
            $child = $property->getValue($this->entity);
            
            if ($child instanceof Collection) {
                // No idea yet :)
            } else if ($child) {
                $builder->add($field, 'entity', array(
                    'class' => get_class($child),
                ));
            }
        }
    }

    public function getDefaultOptions(Array $options)
    {
        return array(
            'data_class' => get_class($this->entity),
        );
    }

    public function getEntity()
    {
        return $this->entity;
    }

    public function setEntity($entity)
    {
        $this->entity = $entity;
        
        return $this;
    }

    public function getEntityManager()
    {
        return $this->em;
    }

    public function setEntityManager($em)
    {
        $this->em = $em;
        
        return $this;
    }

}