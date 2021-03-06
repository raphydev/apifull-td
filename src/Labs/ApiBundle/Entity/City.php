<?php

namespace Labs\ApiBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints AS Assert;
use JMS\Serializer\Annotation as Serializer;
use Hateoas\Configuration\Annotation as Hateoas;




/**
 * City
 *
 * @Hateoas\Relation(
 *     "self",
 *      href = @Hateoas\Route(
 *          "get_city_api_show",
 *          parameters = {"countryId" = "expr(object.getCountry().getId())" ,"id" = "expr(object.getId())" },
 *          absolute = true
 *     ),
 *     exclusion= @Hateoas\Exclusion(
 *          groups={"city"}
 *     )
 * )
 * @Hateoas\Relation(
 *     "create",
 *      href = @Hateoas\Route(
 *          "create_city_api_created",
 *          parameters = {"countryId" = "expr(object.getCountry().getId())"},
 *          absolute = true
 *     ),
 *     exclusion= @Hateoas\Exclusion(
 *          groups={"city"}
 *     )
 * )
 * @Hateoas\Relation(
 *     "updated",
 *      href = @Hateoas\Route(
 *          "update_city_api_updated",
 *          parameters = {"countryId" = "expr(object.getCountry().getId())" ,"id" = "expr(object.getId())" },
 *          absolute = true
 *     ),
 *     exclusion= @Hateoas\Exclusion(
 *          groups={"city"}
 *     )
 * )
 * @Hateoas\Relation(
 *     "delete",
 *      href = @Hateoas\Route(
 *          "remove_city_api_delete",
 *          parameters = {"countryId" = "expr(object.getCountry().getId())" ,"id" = "expr(object.getId())" },
 *          absolute = true
 *     ),
 *     exclusion= @Hateoas\Exclusion(
 *          groups={"city"}
 *     )
 * )
 * @Hateoas\Relation(
 *     "street",
 *      embedded = @Hateoas\Embedded("expr(object.getStreet())"),
 *      exclusion= @Hateoas\Exclusion(
 *          excludeIf = "expr(object.getStreet() === null)",
 *          groups={"city"}
 *     )
 * )
 *
 * @ORM\Table(name="cities", options={"comment":"entity reference city"})
 * @ORM\Entity(repositoryClass="Labs\ApiBundle\Repository\CityRepository")
 */
class City
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @Serializer\Groups({"country","city","street"})
     * @Serializer\Since("0.1")
     */
    protected $id;

    /**
     * @var string
     * @Assert\NotNull(message="Entrez le nom de la ville", groups={"city_default"})
     * @ORM\Column(name="name", type="string", length=255)
     * @Serializer\Groups({"country","city","street"})
     * @Serializer\Since("0.1")
     */
    protected $name;

    /**
     * @var
     * @ORM\ManyToOne(targetEntity="Country", inversedBy="city")
     * @Serializer\Groups({"city"})
     * @Serializer\Since("0.1")
     */
    protected $country;

    /**
     * @var
     * @ORM\OneToMany(targetEntity="Street", mappedBy="city")
     * @Serializer\Groups({"city"})
     * @Serializer\Since("0.1")
     */
    protected $street;


    /**
     * Constructor
     */
    public function __construct()
    {
        $this->street = new ArrayCollection();
    }

    /**
     * Get id
     *
     * @return int
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
     * @return City
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
     * Set country
     *
     * @param  $country
     *
     * @return City
     */
    public function setCountry($country = null)
    {
        $this->country = $country;

        return $this;
    }

    /**
     * Get country
     *
     * @return 
     */
    public function getCountry()
    {
        return $this->country;
    }


    /**
     * Add street
     *
     * @param Street $street
     *
     * @return City
     */
    public function addStreet(Street $street)
    {
        $this->street[] = $street;

        return $this;
    }

    /**
     * Remove street
     *
     * @param Street $street
     */
    public function removeStreet(Street $street)
    {
        $this->street->removeElement($street);
    }

    /**
     * Get street
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getStreet()
    {
        return $this->street;
    }
}
