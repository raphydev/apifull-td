<?php

namespace Labs\ApiBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;
use Gedmo\Mapping\Annotation as Gedmo;
use JMS\Serializer\Annotation as Serializer;
use Hateoas\Configuration\Annotation as Hateoas;


/**
 * Product (information sur les produits)
 *
 * @Hateoas\Relation(
 *     "self",
 *      href = @Hateoas\Route(
 *          "get_product_api_show",
 *          parameters = {
 *              "id" = "expr(object.getId())",
 *              "sectionId" = "expr(object.getSection().getId())",
 *              "storeId" = "expr(object.getStore().getId())"
 *          },
 *          absolute = true
 *     ),
 *     exclusion= @Hateoas\Exclusion(
 *          groups={"products","stores","section"}
 *     )
 * )
 * @Hateoas\Relation(
 *     "create",
 *      href = @Hateoas\Route(
 *          "create_product_api_created",
 *          parameters = {
 *              "sectionId" = "expr(object.getSection().getId())",
 *              "storeId" = "expr(object.getStore().getId())"
 *          },
 *          absolute = true
 *     ),
 *     exclusion= @Hateoas\Exclusion(
 *          groups={"products","stores","section"}
 *     )
 * )
 *
 * @Hateoas\Relation(
 *     "updated",
 *      href = @Hateoas\Route(
 *          "update_product_api_updated",
 *          parameters = {
 *              "id" = "expr(object.getId())",
 *              "sectionId" = "expr(object.getSection().getId())",
 *              "storeId" = "expr(object.getStore().getId())"
 *          },
 *          absolute = true
 *     ),
 *     exclusion= @Hateoas\Exclusion(
 *          groups={"products","stores","section"}
 *     )
 * )
 *
 * @Hateoas\Relation(
 *     "patch_product_brand",
 *      href = @Hateoas\Route(
 *          "patch_brand_product_api_product_brand",
 *          parameters = {
 *              "id" = "expr(object.getId())"
 *          },
 *          absolute = true
 *     ),
 *     exclusion= @Hateoas\Exclusion(
 *          groups={"products","stores","section"}
 *     )
 * )
 *
 * @Hateoas\Relation(
 *     "remove",
 *      href = @Hateoas\Route(
 *          "remove_product_api_delete",
 *          parameters = {
 *              "id" = "expr(object.getId())",
 *              "sectionId" = "expr(object.getSection().getId())",
 *              "storeId" = "expr(object.getStore().getId())"
 *          },
 *          absolute = true
 *     ),
 *     exclusion= @Hateoas\Exclusion(
 *          groups={"products","stores","section"}
 *     )
 * )
 *
 * @Hateoas\Relation(
 *     "brands",
 *      embedded = @Hateoas\Embedded("expr(object.getBrand())"),
 *      exclusion= @Hateoas\Exclusion(
 *          excludeIf = "expr(object.getBrand() === null)",
 *          groups={"products","brands"}
 *     )
 * )
 *
 * @Hateoas\Relation(
 *     "colors",
 *      embedded = @Hateoas\Embedded("expr(object.getColor())"),
 *      exclusion= @Hateoas\Exclusion(
 *          excludeIf = "expr(object.getColor() === null)",
 *          groups={"products","colors"}
 *     )
 * )
 *
 * @Hateoas\Relation(
 *     "sizes",
 *      embedded = @Hateoas\Embedded("expr(object.getSize())"),
 *      exclusion= @Hateoas\Exclusion(
 *          excludeIf = "expr(object.getSize() === null)",
 *          groups={"products","sizes"}
 *     )
 * )
 * @Hateoas\Relation(
 *     "prices",
 *      embedded = @Hateoas\Embedded("expr(object.getPrice())"),
 *      exclusion= @Hateoas\Exclusion(
 *          excludeIf = "expr(object.getPrice() === null)",
 *          groups={"products","prices"}
 *     )
 * )
 *
 * @ORM\Table("products")
 * @ORM\Entity(repositoryClass="Labs\ApiBundle\Repository\ProductRepository")
 * @ORM\HasLifecycleCallbacks()
 * @UniqueEntity(fields={"sku"}, groups={"product_sku"} ,message="Erreur de system")
 */
class Product
{

    /**
     * @ORM\Column(type="integer", name="id")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @Serializer\Groups({"products"})
     * @Serializer\Since("0.1")
     */
    protected $id;


    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     * @Assert\NotBlank(message="Entrez le nom de l'article", groups={"product_default"})
     * @Assert\NotNull(message="Ce champs ne peut être vide", groups={"product_default"})
     * @Serializer\Groups({"products"})
     * @Serializer\Since("0.1")
     */
    protected $name;


    /**
     * @var int
     * @Assert\NotBlank(message="Entrez le stock minimum du produit", groups={"stock_products"})
     * @Assert\Type(
     *     type="integer",
     *     message="La valeur {{ value }} n'est pas valide {{ type }}.",
     *     groups={"stock_products"}
     * )
     * @ORM\Column(name="stock_min", type="integer")
     * @Serializer\Groups({"products"})
     * @Serializer\Since("0.1")
     */
    protected $stockMin;


    /**
     * @var int
     * @Assert\NotBlank(message="Entrez le stock de sécuriré de l'article", groups={"stock_products"})
     * @Assert\Type(
     *     type="integer",
     *     message="La valeur {{ value }} n'est pas valide {{ type }}.",
     *     groups={"stock_products"}
     * )
     * @ORM\Column(name="secure_stock", type="integer")
     * @Serializer\Groups({"products"})
     * @Serializer\Since("0.1")
     */
    protected $secureStock;

    /**
     * @var
     * @ORM\Column(nullable=true, type="string", name="length", length=10)
     * @Serializer\Groups({"products"})
     * @Serializer\Since("0.1")
     */
    protected $length;

    /**
     * @var
     * @ORM\Column(nullable=true, type="string", name="weight", length=10)
     * @Serializer\Groups({"products"})
     * @Serializer\Since("0.1")
     */
    protected $weight;

    /**
     * @var
     * @ORM\Column(nullable=true, type="string", name="pound", length=10)
     * @Serializer\Groups({"products"})
     * @Serializer\Since("0.1")
     */
    protected $pound;

    /**
     * @var
     * @ORM\Column(nullable=true, type="string", name="unit", length=10)
     * @Serializer\Groups({"products"})
     * @Serializer\Since("0.1")
     */
    protected $unit;


    /**
     * @Gedmo\Slug(fields={"name"}, updatable=true, separator="_")
     * @ORM\Column(length=128, unique=true)
     * @Serializer\Groups({"products"})
     * @Serializer\Since("0.1")
     */
    protected $slug;

    /**
     * @var string $sku
     *
     * @ORM\Column(name="sku", type="string", length=255, unique=true, nullable=true)
     * @Serializer\Groups({"products"})
     * @Serializer\Since("0.1")
     */
    protected $sku;

    /**
     * @var string
     *
     * @ORM\Column(name="content", type="text")
     * @Assert\NotBlank(message="Entrez la description de l'article ", groups={"product_default"})
     * @Assert\NotNull(message="La description est vide", groups={"product_default"})
     * @Serializer\Groups({"products"})
     * @Serializer\Since("0.1")
     */
    protected $content;


    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created", type="date")
     * @Serializer\Groups({"products"})
     * @Serializer\Since("0.1")
     */
    protected $created;

    /**
     * @var
     * @ORM\Column(name="status", type="boolean")
     * @Serializer\Groups({"products"})
     * @Serializer\Since("0.1")
     */
    protected $status;


    /**
     * @ORM\ManyToOne(targetEntity="Section", inversedBy="products")
     * @Serializer\Groups({"products","section_products"})
     * @Serializer\Since("0.1")
     */
    protected $section;

    /**
     * @ORM\ManyToOne(targetEntity="Brand", inversedBy="products")
     * @Serializer\Groups({"products"})
     * @Serializer\Since("0.1")
     */
    protected $brand;

    /**
     * @ORM\ManyToOne(targetEntity="Store", inversedBy="products")
     * @Serializer\Groups({"products"})
     * @Serializer\Since("0.1")
     */
    protected $store;

    /**
     * @var
     * @ORM\ManyToMany(targetEntity="Color", inversedBy="products")
     * @ORM\JoinTable(name="products_colors")
     * @Serializer\Groups({"products"})
     * @Serializer\Since("0.1")
     */
    protected $color;

    /**
     * @var
     * @ORM\ManyToMany(targetEntity="Size", inversedBy="products")
     * @ORM\JoinTable(name="products_sizes")
     * @Serializer\Groups({"products"})
     * @Serializer\Since("0.1")
     */
    protected $size;

    /**
     * @var
     * @ORM\OneToMany(targetEntity="Media", mappedBy="product")
     * @Serializer\Groups({"products"})
     * @Serializer\Since("0.1")
     */
    protected $medias;

    /**
     * @var
     * @ORM\OneToMany(targetEntity="Price", mappedBy="product")
     * @Serializer\Groups({"products"})
     * @Serializer\Since("0.1")
     */
    protected $price;

    /**
     * @var
     * @ORM\OneToMany(targetEntity="Stock", mappedBy="product")
     * @Serializer\Groups({"products_stock"})
     * @Serializer\Since("0.1")
     */
    protected $stocks;

    /**
     * @var
     * @ORM\OneToMany(targetEntity="Promotion", mappedBy="product")
     * @Serializer\Groups({"products"})
     * @Serializer\Since("0.1")
     */
    protected $promotions;

    /**
     * @var
     * @ORM\OneToMany(targetEntity="OrderProduct", mappedBy="product")
     */
    protected $orderproduct;

    
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->color = new ArrayCollection();
        $this->size = new ArrayCollection();
        $this->medias = new ArrayCollection();
        $this->price = new ArrayCollection();
        $this->stocks = new ArrayCollection();
        $this->orderproduct = new ArrayCollection();
        $this->promotions = new ArrayCollection();
        $this->status = true;
    }

    /**
     * Get id
     *
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return Product
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
     * Set slug
     *
     * @param string $slug
     *
     * @return Product
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * Get slug
     *
     * @return string
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * Set sku
     *
     * @param string $sku
     *
     * @return Product
     */
    public function setSku($sku)
    {
        $this->sku = $sku;

        return $this;
    }

    /**
     * Get sku
     *
     * @return string
     */
    public function getSku()
    {
        return $this->sku;
    }


    /**
     * Set content
     *
     * @param string $content
     *
     * @return Product
     */
    public function setContent($content)
    {
        $this->content = $content;

        return $this;
    }

    /**
     * Get $content
     *
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }


    /**
     * Set created
     *
     * @param \DateTime $created
     *
     * @return Product
     */
    public function setCreated($created)
    {
        $this->created = $created;

        return $this;
    }

    /**
     * Get created
     *
     * @return \DateTime
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * Set brand
     *
     * @param Brand $brand
     *
     * @return Product
     */
    public function setBrand(Brand $brand = null)
    {
        $this->brand = $brand;

        return $this;
    }

    /**
     * Get brand
     *
     * @return Brand
     */
    public function getBrand()
    {
        return $this->brand;
    }



    /**
     * Set section
     *
     * @param Section $section
     *
     * @return Product
     */
    public function setSection(Section $section = null)
    {
        $this->section = $section;

        return $this;
    }

    /**
     * Get section
     *
     * @return Section
     */
    public function getSection()
    {
        return $this->section;
    }


    /**
     * Add color
     *
     * @param Color $color
     *
     * @return Product
     */
    public function addColor(Color $color)
    {
        $this->color[] = $color;

        return $this;
    }

    /**
     * Remove color
     *
     * @param Color $color
     */
    public function removeColor(Color $color)
    {
        $this->color->removeElement($color);
    }

    /**
     * Get color
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getColor()
    {
        return $this->color;
    }

    /**
     * Add size
     *
     * @param Size $size
     *
     * @return Product
     */
    public function addSize(Size $size)
    {
        $this->size[] = $size;

        return $this;
    }

    /**
     * Remove size
     *
     * @param Size $size
     */
    public function removeSize(Size $size)
    {
        $this->size->removeElement($size);
    }

    /**
     * Get size
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getSize()
    {
        return $this->size;
    }

    /**
     * Set store
     *
     * @param Store $store
     *
     * @return Product
     */
    public function setStore(Store $store = null)
    {
        $this->store = $store;

        return $this;
    }

    /**
     * Get store
     *
     * @return Store
     */
    public function getStore()
    {
        return $this->store;
    }

    /**
     * @ORM\PrePersist()
     */
    public function AddCreated(){
        return $this->created = new \DateTime('now');
    }


    /**
     * Set length
     *
     * @param string $length
     *
     * @return Product
     */
    public function setLength($length)
    {
        $this->length = $length;

        return $this;
    }

    /**
     * Get length
     *
     * @return string
     */
    public function getLength()
    {
        return $this->length;
    }

    /**
     * Set weight
     *
     * @param string $weight
     *
     * @return Product
     */
    public function setWeight($weight)
    {
        $this->weight = $weight;

        return $this;
    }

    /**
     * Get weight
     *
     * @return string
     */
    public function getWeight()
    {
        return $this->weight;
    }

    /**
     * Set pound
     *
     * @param string $pound
     *
     * @return Product
     */
    public function setPound($pound)
    {
        $this->pound = $pound;

        return $this;
    }

    /**
     * Get pound
     *
     * @return string
     */
    public function getPound()
    {
        return $this->pound;
    }

    /**
     * Set unit
     *
     * @param string $unit
     *
     * @return Product
     */
    public function setUnit($unit)
    {
        $this->unit = $unit;

        return $this;
    }

    /**
     * Get unit
     *
     * @return string
     */
    public function getUnit()
    {
        return $this->unit;
    }



    /**
     * Add media
     *
     * @param Media $media
     *
     * @return Product
     */
    public function addMedia(Media $media)
    {
        $this->medias[] = $media;

        return $this;
    }

    /**
     * Remove media
     *
     * @param Media $media
     */
    public function removeMedia(Media $media)
    {
        $this->medias->removeElement($media);
    }

    /**
     * Get medias
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getMedias()
    {
        return $this->medias;
    }


    /**
     * Add price
     *
     * @param Price $price
     *
     * @return Product
     */
    public function addPrice(Price $price)
    {
        $this->price[] = $price;

        return $this;
    }

    /**
     * Remove price
     *
     * @param Price $price
     */
    public function removePrice(Price $price)
    {
        $this->price->removeElement($price);
    }

    /**
     * Get price
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * Add stock
     *
     * @param Stock $stock
     *
     * @return Product
     */
    public function addStock(Stock $stock)
    {
        $this->stocks[] = $stock;

        return $this;
    }

    /**
     * Remove stock
     *
     * @param Stock $stock
     */
    public function removeStock(Stock $stock)
    {
        $this->stocks->removeElement($stock);
    }

    /**
     * Get stocks
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getStocks()
    {
        return $this->stocks;
    }


    /**
     * Set stockMin.
     *
     * @param int $stockMin
     *
     * @return Product
     */
    public function setStockMin($stockMin)
    {
        $this->stockMin = $stockMin;

        return $this;
    }

    /**
     * Get stockMin.
     *
     * @return int
     */
    public function getStockMin(): int
    {
        return $this->stockMin;
    }

    /**
     * Set secureStock.
     *
     * @param int $secureStock
     *
     * @return Product
     */
    public function setSecureStock($secureStock)
    {
        $this->secureStock = $secureStock;

        return $this;
    }

    /**
     * Get secureStock.
     *
     * @return int
     */
    public function getSecureStock()
    {
        return $this->secureStock;
    }

    /**
     * @return mixed
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param $status
     *
     * @return $this
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }



    /**
     * Add orderproduct.
     *
     * @param OrderProduct $orderproduct
     *
     * @return Product
     */
    public function addOrderproduct(OrderProduct $orderproduct)
    {
        $this->orderproduct[] = $orderproduct;

        return $this;
    }

    /**
     * Remove orderproduct.
     *
     * @param OrderProduct $orderproduct
     *
     * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removeOrderproduct(OrderProduct $orderproduct)
    {
        return $this->orderproduct->removeElement($orderproduct);
    }

    /**
     * Get orderproduct.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getOrderproduct()
    {
        return $this->orderproduct;
    }

    /**
     * Add promotion.
     *
     * @param Promotion $promotion
     *
     * @return Product
     */
    public function addPromotion(Promotion $promotion)
    {
        $this->promotions[] = $promotion;

        return $this;
    }

    /**
     * Remove promotion.
     *
     * @param Promotion $promotion
     *
     * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removePromotion(Promotion $promotion)
    {
        return $this->promotions->removeElement($promotion);
    }

    /**
     * Get promotions.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPromotions()
    {
        return $this->promotions;
    }
}
