<?php


namespace App\FrontEndBundle\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{
    /**
     * @Route("", name="app_home")
     * @return Response
     */
    public function indexAction()
    {
        return $this->render('@FrontEndBundle/index.html.twig',[]);
    }
}