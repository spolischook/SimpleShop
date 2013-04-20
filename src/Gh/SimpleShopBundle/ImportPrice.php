<?php

namespace Gh\SimpleShopBundle;

use Gh\SimpleShopBundle\Entity\Category;
use Gh\SimpleShopBundle\Entity\Product;
use Symfony\Component\DomCrawler\Crawler;
use Doctrine\ORM\EntityManager;
use Doctrine\Common\DataFixtures\ReferenceRepository;

class ImportPrice
{
    /** @var EntityManager */
    private $em;

    private $rootDir;

    /** @var Crawler */
    private $crawler;

    /**
     * Fixture reference repository
     *
     * @var ReferenceRepository
     */
    protected $referenceRepository;

    /** @var \SplObjectStorage */
    private $products;

    /** @var \SplObjectStorage */
    private $categories;

    /** @var Category */
    private $currentCategory;

    /** @var integer */
    private $nodesCount;

    /** @var Crawler */
    private $nodes;

    public function __construct(EntityManager $entityManager, $rootDir)
    {
        $this->em = $entityManager;
        $this->rootDir = $rootDir;

        $this->crawler = new Crawler();
        $this->referenceRepository = new ReferenceRepository($entityManager);
        $this->products = new \SplObjectStorage();
        $this->categories = new \SplObjectStorage();
    }

    public function importNode()
    {
        if (!$this->nodes->valid()) {
            throw new \Exception('Node is not valid');
        }

        $node = new Crawler($this->nodes->current());

        if ($node->attr('isgroup') == 1) {
            $this->currentCategory = $this->getCategoryFromNode($node);
            $this->categories->attach($this->currentCategory);
            $this->referenceRepository->setReference('category'.$this->currentCategory->getSku(), $this->currentCategory);
        }
        else {
            $product = $this->getProductFromNode($node, $this->currentCategory);
            $this->products->attach($product);
        }
    }

    public function importPriceFile($priceFile)
    {
        $filePath = $this->rootDir . '/../' . $priceFile;

        if (!file_exists($filePath)) {
            throw new \Exception('File ' . $filePath . ' not exist');
        }

        $xmlContent = file_get_contents($filePath);
        $this->crawler->addXmlContent($xmlContent);

        $this->nodesCount = count($this->crawler->filter('node'));
        $this->nodes = $this->crawler->filter('node');
        $this->nodes->rewind();
        $this->nodes->current();

        return $this->nodesCount;
    }

    public function flush()
    {
        foreach ($this->categories as $category) {
            $this->em->persist($category);
        }

        foreach ($this->products as $product) {
            $this->em->persist($product);
        }

        $this->em->flush();
    }

    public function getNodesCount()
    {
        return $this->nodesCount;
    }

    public function nextNode()
    {
        $this->nodes->next();
    }

    private function getProductFromNode(Crawler $node, Category $category)
    {
        $product = new Product();
        $product->setName($node->filter('name')->text())
            ->setSku($node->attr('kod'))
            ->setDescription($node->filter('fullname')->text())
            ->setQuantity($this->getQuantity($node))
            ->setManufacturer($node->filter('manufacturer')->text())
            ->setPartNumber($node->filter('partnumber')->text())
            ->setPriceIn($node->attr('cena_dyler'))
            ->setPriceOut($node->attr('cena_rozdrib'))
            ->setCategory($category);

        return $product;
    }

    private function getCategoryFromNode(Crawler $node)
    {
        $category = new Category();

        $category->setName($node->filter('name')->text())
            ->setSku($node->attr('kod'));

        if ($node->parents()->attr('kod')) {
            $parentCategory = $this->referenceRepository->getReference('category'.$node->parents()->attr('kod'));
            $category->setParent($parentCategory);
        }

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
}