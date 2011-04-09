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

    public function newAction($slug)
    {
        return $this->editAction($slug);
    }

    public function editAction($slug, $id = null)
    {
        $model  =   $this->getModel($slug);
        $entity =   $model->getEntity($id);
        $form   =   $model->getForm($entity);
        $request    =   $this->get('request');
        
        if ('POST' === $request->getMethod()) {
            $form->bindRequest($request);
            
            if ($form->isValid()) {
                $em = $model->getEntityManager();
                
                $em->persist($form->getData());
                $em->flush();
                
                $this->get('session')->setFlash('success', 'Saved');
            }
        }
        
        $renderer = $this->get('form.factory')->createRenderer($form, 'twig');
        $renderer->setTemplate('CodeMemeAdminBundle:Form:default.html.twig');
        
        return $this->render('CodeMemeAdminBundle:Model:edit.html.twig', array(
            'model'     =>  $model,
            'entity'    =>  $entity,
            'form'      =>  $renderer,
        ));
    }

    protected function getModel($slug)
    {
        return $this->get('admin.models')->get(str_replace('-', '_', $slug));
    }

}