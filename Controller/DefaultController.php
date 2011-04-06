<?php

namespace CodeMeme\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{

    public function indexAction()
    {
        return $this->render('CodeMemeAdminBundle:Default:index.html.twig', array(
            'models' => $this->get('admin.models'),
        ));
    }

}