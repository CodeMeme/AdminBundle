<?php

namespace CodeMeme\AdminBundle\Form;

use Doctrine\Common\Collections\Collection;

use Symfony\Component\Form\Type\AbstractType;
use Symfony\Component\Form\FormBuilder;

class ModelFormType extends AbstractType
{

    private $entity;

    private $em;

    private $fields;

    public function __construct($entity, $em)
    {
        $this->entity   =   $entity;
        $this->em       =   $em;
    }

    public function buildForm(FormBuilder $builder, Array $options)
    {
        foreach ($this->getFields() as $field) {
            $builder->add($field);
        }
    }

    public function getFields()
    {
        if (null === $this->fields) {
            $metadata = $this->em->getClassMetadata(get_class($this->entity));
            
            $this->setFields(array_merge(
                array_keys($metadata->fieldMappings),
                array_keys($metadata->associationMappings)
            ));
        }
        
        return $this->fields;
    }

    public function setFields(Array $fields)
    {
        $this->fields = $fields;
        
        return $this;
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