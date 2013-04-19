<?php

namespace Gh\SimpleShopBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\HttpFoundation\Response;
use Gh\SimpleShopBundle\Entity\Product;
use Gh\SimpleShopBundle\Entity\Category;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class IndexController extends Controller
{
    /**
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

//        $filePath = $this->get('kernel')->getRootdir().'/../dylerprice.xml';
        $filePath = $this->get('kernel')->getRootdir().'/../price.xml';
        $xmlContent = file_get_contents($filePath);

        $crawler = new Crawler();

        $document = new \DOMDocument();
        $document->loadXml($xmlContent);
        $crawler->add($document);

        $countNodes = $this->getNodesCount($crawler);
        $objArray = new \SplObjectStorage();

        for ($i=0; $i < $countNodes; $i++) {
            $node = $crawler->filter('node')->eq($i);

//            $node->attr('isgroup') == 1
//                ? $em->persist($this->addCategory($node))
//                : $em->persist($this->addProduct($node));
            if ($node->attr('isgroup') == 1) {
                $category = $this->addCategory($node);
                $em->persist($category);
                $objArray->attach($category);
            }
            else {
                $product = $this->addProduct($node);
                $em->persist($product);
                $objArray->attach($product);
            }
        }

        $em->flush();

        return array('objArray' => $objArray);
    }

    private function addProduct(Crawler $node)
    {
        $product = new Product();
        $product->setName($node->filter('name')->text());
        $product->setSku($node->attr('kod'));
        $product->setDescription($node->filter('fullname')->text());
        $product->setQuantity($this->getQuantity($node));
        $product->setManufacturer($node->filter('manufacturer')->text());
        $product->setPartNumber($node->filter('partnumber')->text());
        $product->setPriceIn($node->attr('cena_dyler'));
        $product->setPriceOut($node->attr('cena_rozdrib'));

        return $product;
    }

    private function addCategory(Crawler $node)
    {
        $category = new Category();

        $category->setName($node->filter('name')->text());
        $category->setSku($node->attr('kod'));

        return $category;
    }

    private function getQuantity(Crawler $node)
    {
        if ($node->attr('isgroup') == 1) {
            return 0;
        }

        return (int)$node->attr('ostatok_kyyiv')
            + (int)$node->attr('ostatok_lviv')
            + (int)$node->attr('ostatok_odesa')
        ;
    }

    private function getNodesCount(Crawler $crawler)
    {
        return count($crawler->filter('node'));
    }
}
