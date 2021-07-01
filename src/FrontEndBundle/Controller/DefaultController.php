<?php


namespace App\FrontEndBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{

    /**
     * @Route("/{filter}", name="app_home", defaults={"filter":"all"}, requirements={"filter":"more_than_5|all|out_of_stock"})
     * @param string $filter
     * @return Response
     */
    public function indexAction(string $filter): Response
    {
        return $this->render('@FrontEndBundle/index.html.twig',[
            'filter'=>$filter
        ]);
    }

    /**
     * @Route("/product/{id}/edit", name="app_product_form", requirements={"id":"\d+"}, defaults={"id":0})
     * @Route("/product/new/", name="app_product_new")
     * @param int $id
     * @return Response
     */
    public function formAction(int $id=0): Response
    {
        return $this->render('@FrontEndBundle/form.html.twig',[
            'id'=>$id
        ]);
    }
}