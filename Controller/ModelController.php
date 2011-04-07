<?php

namespace CodeMeme\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class ModelController extends Controller
{

    public function listAction($slug)
    {
        $model      =   $this->getModel($slug);
        $entities   =   $model->getRepository()->findAll();
        
        return $this->render('CodeMemeAdminBundle:Model:list.html.twig', array(
            'model'     =>  $model,
            'entities'  =>  $entities,
        ));
    }

    public function editAction($slug, $id)
    {
        $model  =   $this->getModel($slug);
        $entity =   $model->getRepository()->find($id);
        $form   =   'Form';
        
        return $this->render('CodeMemeAdminBundle:Model:edit.html.twig', array(
            'model'     =>  $model,
            'entity'    =>  $entity,
            'form'      =>  $form,
        ));
    }

    protected function getModel($slug)
    {
        return $this->get(sprintf('admin.%s_model', $slug));
    }

}