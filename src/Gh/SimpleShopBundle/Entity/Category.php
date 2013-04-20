<?php

namespace Gh\SimpleShopBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Category
 *
 * @ORM\Table(name="category")
 * @ORM\Entity
 */
class Category
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var integer
     *
     * @ORM\Column(name="sku", type="integer")
     */
    private $sku;

    /** @ORM\OneToMany(targetEntity="Product", mappedBy="category") */
    private $products;

    /** @ORM\ManyToOne(targetEntity="Category", inversedBy="children") */
    private $parent;

    /** @ORM\OneToMany(targetEntity="Category", mappedBy="parent") */
    private $children;

    public function __construct()
    {
        $this->products = new ArrayCollection();
        $this->children = new ArrayCollection();
    }

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return Category
     */
    public function setName($name)
    {
        $this->name = $name;
    
        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set sku
     *
     * @param integer $sku
     * @return Category
     */
    public function setSku($sku)
    {
        $this->sku = $sku;
    
        return $this;
    }

    /**
     * Get sku
     *
     * @return integer 
     */
    public function getSku()
    {
        return $this->sku;
    }

    /**
     * Add products
     *
     * @param \Gh\SimpleShopBundle\Entity\Product $products
     * @return Category
     */
    public function addProduct(\Gh\SimpleShopBundle\Entity\Product $products)
    {
        $this->products[] = $products;
    
        return $this;
    }

    /**
     * Remove products
     *
     * @param \Gh\SimpleShopBundle\Entity\Product $products
     */
    public function removeProduct(\Gh\SimpleShopBundle\Entity\Product $products)
    {
        $this->products->removeElement($products);
    }

    /**
     * Get products
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getProducts()
    {
        return $this->products;
    }

    /**
     * Set parent
     *
     * @param \Gh\SimpleShopBundle\Entity\Category $parent
     * @return Category
     */
    public function setParent(\Gh\SimpleShopBundle\Entity\Category $parent = null)
    {
        $this->parent = $parent;
    
        return $this;
    }

    /**
     * Get parent
     *
     * @return \Gh\SimpleShopBundle\Entity\Category 
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * Add children
     *
     * @param \Gh\SimpleShopBundle\Entity\Category $children
     * @return Category
     */
    public function addChildren(\Gh\SimpleShopBundle\Entity\Category $children)
    {
        $this->children[] = $children;
    
        return $this;
    }

    /**
     * Remove children
     *
     * @param \Gh\SimpleShopBundle\Entity\Category $children
     */
    public function removeChildren(\Gh\SimpleShopBundle\Entity\Category $children)
    {
        $this->children->removeElement($children);
    }

    /**
     * Get children
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getChildren()
    {
        return $this->children;
    }
}