<?php

namespace Gh\SimpleShopBundle\Controller;

use FOS\RestBundle\Controller\FOSRestController;

class ApiController extends FOSRestController
{
    public function getProductsAction($page)
    {
        $products = $this->getDoctrine()
            ->getRepository('SimpleShopBundle:Product')
            ->findBy(array(), array(), 10, ($page-1)*10)
        ;

        $view = $this->view($products, 200, array('charset' => 'windows-1251'));
        $view->setFormat('json');

        return $this->handleView($view);
    }

    public function getCategoryProductsAction($id)
    {
        $category = $this->getDoctrine()
            ->getRepository('SimpleShopBundle:Category')
            ->findOneById($id)
        ;

        $view = $this->view($category->getProducts(), 200, array('charset' => 'windows-1251'));
        $view->setFormat('json');

        return $this->handleView($view);
    }
}