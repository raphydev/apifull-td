<?php

namespace Labs\ApiBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;
use JMS\Serializer\Annotation as Serializer;
use libphonenumber\PhoneNumber;
use Symfony\Component\Validator\Constraints AS Assert;
use Misd\PhoneNumberBundle\Validator\Constraints\PhoneNumber as AssertPhoneNumber;
use Gedmo\Mapping\Annotation as Gedmo;


/**
 *
 * @ORM\Table(name="users", options={"comment":"entity reference Users"})
 * @ORM\Entity(repositoryClass="Labs\ApiBundle\Repository\UserRepository")
 * @ORM\HasLifecycleCallbacks()
 * @UniqueEntity(fields={"email"},groups={"registration"} ,message="Cette adresse email existe déjà ")
 * @UniqueEntity(fields={"phone"}, groups={"registration"} ,message="Ce numero de téléphone est déjà utilisé")
 * @UniqueEntity(fields={"profileName"}, groups={"profilpage"} ,message="Ce nom d'utilisation n'est pas disponible")
 * @UniqueEntity(fields={"username"}, groups={"profilpage"} ,message="Ce nom d'utilisation n'est pas disponible")
 */
class User implements UserInterface
{

    /**
     * @ORM\Column(name="id", type="guid")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="UUID")
     * @Serializer\Groups({"logged","store_groups","users"})
     * @Serializer\Since("0.1")
     */
    protected $id;

    /**
     * @ORM\Column(type="string", length=25, unique=true)
     * @Serializer\Since("0.1")
     * @Serializer\Groups({"logged","users"})
     */
    protected $username;

    /**
     * @ORM\Column(type="string", length=500)
     * @Assert\NotNull(message="Entrez un mot de passe", groups={"registration"})
     * @Serializer\Since("0.1")
     */
    protected $password;

    /**
     * @var PhoneNumber
     * @Assert\NotNull(message="Entrez un numero valide", groups={"registration"})
     * @Serializer\Type("libphonenumber\PhoneNumber")
     * @ORM\Column(name="phone", type="phone_number", unique=true, nullable=true)
     * @Serializer\Groups({"logged","store_groups","users"})
     * @Serializer\Since("0.1")
     */
    protected $phone;

    /**
     * @var string
     * @Assert\NotNull(message="Entrez une adresse email", groups={"registration"})
     * @Assert\Email(message="le format de l'adresse email est invalide", groups={"registration"})
     * @ORM\Column(name="email", type="string", length=255, nullable=true)
     * @Serializer\Groups({"logged","store_groups","users"})
     * @Serializer\Since("0.1")
     */
    protected $email;

    /**
     * @var string
     * @Assert\NotNull(message="Veuillez renseigner votre nom", groups={"seller_registration"})
     * @ORM\Column(name="firstname", type="string", length=225, nullable=true)
     * @Serializer\Groups({"store_groups","users"})
     * @Serializer\Since("0.1")
     */
    protected $firstname;

    /**
     * @var string
     * @Assert\NotNull(message="Veuillez renseigner votre prénom", groups={"seller_registration"})
     * @ORM\Column(name="lastname", type="string", length=225, nullable=true)
     * @Serializer\Since("0.1")
     * @Serializer\Groups({"store_groups","users"})
     */
    protected $lastname;

    /**
     * @var int
     *
     * @ORM\Column(
     *     name="code_validation",
     *     type="integer",
     *     length=5, nullable=true,
     *     options={"comment":"Code validation envoyez sur le téléphone et le mail d'un vendeur"}
     * )
     * @Serializer\Groups({"logged"})
     * @Serializer\SerializedName("codeValidation")
     * @Serializer\Since("0.1")
     */
    protected $codeValidation;

    /**
     * @Gedmo\Slug(fields={"firstname","codeValidation"}, updatable=true, separator=".")
     * @ORM\Column(length=128, unique=true)
     * @Serializer\Groups({"logged","store_groups","users"})
     * @Serializer\Since("0.1")
     */
    protected $slug;

    /**
     * @var
     * @Assert\NotNull(message="Entrez un nom de profile", groups={"profilpage"})
     * @ORM\Column(nullable=true, unique=true, name="profile_name", length=255)
     * @Serializer\Groups({"logged","store_groups","users"})
     * @Serializer\Since("0.1")
     */
    protected $profileName;

    /**
     * @var
     * @ORM\Column(name="updated", type="datetime", nullable=true)
     * @Serializer\Since("0.1")
     */
    protected $updated;


    /**
     * @var
     * @ORM\Column(type="json_array", nullable=true)
     * @Serializer\Since("0.1")
     * @Serializer\Groups({"store_groups","users","logged"})
     */
    protected $roles = [];

    /**
     * @ORM\Column(name="is_active", type="boolean")
     * @Serializer\Since("0.1")
     */
    protected $isActive;


    /**
     * @var
     * @ORM\OneToMany(targetEntity="Store", mappedBy="user")
     * @Serializer\Since("0.1")
     * @Serializer\Groups({"logged"})
     */
    protected $store;

    /**
     * @var
     * @ORM\OneToMany(targetEntity="Notification", mappedBy="user")
     * @Serializer\Since("0.1")
     * @Serializer\Groups({"users_notification","logged"})
     */
    protected $notification;


    /**
     * @var
     * @ORM\Column(name="created", type="datetime",nullable=true)
     * @Serializer\Groups({"logged","users"})
     * @Serializer\Since("0.1")
     */
    protected $created;

    /**
     * @var
     * @ORM\OneToMany(targetEntity="Command", mappedBy="user")
     * @Serializer\Groups({"users"})
     * @Serializer\Since("0.1")
     */
    protected $command;



    public function __construct()
    {
        $this->isActive = true;
        $this->created = new \DateTime('now');
        $this->store = new ArrayCollection();
        $this->notification = new ArrayCollection();
        $this->command = new ArrayCollection();
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
     * Set username
     *
     * @param string $username
     *
     * @return User
     */
    public function setUsername($username)
    {
        $this->username = $username;

        return $this;
    }

    public function getUsername()
    {
        return $this->username;
    }

    public function getSalt()
    {
        return null;
    }

    public function getPassword()
    {
        return $this->password;
    }

    public function setPassword($password)
    {
        $this->password = $password;
    }


    public function eraseCredentials()
    {
    }
    

    /**
     * {@inheritdoc}
     */
    public function getRoles()
    {
        $roles = $this->roles;
        return array_unique($roles);
    }
    /**
     * {@inheritdoc}
     */
    public function hasRole($role)
    {
        return in_array(strtoupper($role), $this->getRoles(), true);
    }

    /**
     * Set roles
     *
     * @param array $roles
     *
     * @return User
     */
    public function setRoles( array $roles)
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * Set phone
     *
     * @param PhoneNumber
     *
     * @return User
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;

        return $this;
    }

    /**
     * Get phone
     *
     * @return PhoneNumber
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * Set email
     *
     * @param string $email
     *
     * @return User
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set firstname
     *
     * @param string $firstname
     *
     * @return User
     */
    public function setFirstname($firstname)
    {
        $this->firstname = $firstname;

        return $this;
    }

    /**
     * Get firstname
     *
     * @return string
     */
    public function getFirstname()
    {
        return $this->firstname;
    }

    /**
     * Set lastname
     *
     * @param string $lastname
     *
     * @return User
     */
    public function setLastname($lastname)
    {
        $this->lastname = $lastname;

        return $this;
    }

    /**
     * Get lastname
     *
     * @return string
     */
    public function getLastname()
    {
        return $this->lastname;
    }


    /**
     * Set codeValidation
     *
     * @param integer $codeValidation
     *
     * @return User
     */
    public function setCodeValidation($codeValidation)
    {
        $this->codeValidation = $codeValidation;

        return $this;
    }

    /**
     * Get codeValidation
     *
     * @return integer
     */
    public function getCodeValidation()
    {
        return $this->codeValidation;
    }

    /**
     * Set slug
     *
     * @param string $slug
     *
     * @return User
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
     * Set created
     *
     * @param \DateTime $created
     *
     * @return User
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
     * Set profileName
     *
     * @param string $profileName
     *
     * @return User
     */
    public function setProfileName($profileName)
    {
        $this->profileName = $profileName;

        return $this;
    }

    /**
     * Get profileName
     *
     * @return string
     */
    public function getProfileName()
    {
        return $this->profileName;
    }

    /**
     * Set updated
     *
     * @param \DateTime $updated
     *
     * @return User
     */
    public function setUpdated($updated)
    {
        $this->updated = $updated;

        return $this;
    }

    /**
     * Get updated
     *
     * @return \DateTime
     */
    public function getUpdated()
    {
        return $this->updated;
    }

    /**
     * @ORM\PreUpdate()
     * @ORM\PrePersist()
     */
    public function updateDate()
    {
        $this->updated = new \DateTime('now');
    }

    /**
     * @Serializer\VirtualProperty()
     * @param string $separation
     * @return null|string
     * @Serializer\Groups({"logged","store_groups","users"})
     * @Serializer\SerializedName("userNamed")
     */
    public function getUserNamed($separation = ' ')
    {
        if (null !== $this->getFirstname() || null !== $this->getLastname()) {
            return $this->getFirstname().$separation.$this->getLastname();
        }else {
            return null;
        }
    }



    /**
     * Set isActive
     *
     * @param boolean $isActive
     *
     * @return User
     */
    public function setIsActive($isActive)
    {
        $this->isActive = $isActive;

        return $this;
    }

    /**
     * Get isActive
     *
     * @return boolean
     */
    public function getIsActive()
    {
        return $this->isActive;
    }

    /**
     * Add store
     *
     * @param Store $store
     *
     * @return User
     */
    public function addStore(Store $store)
    {
        $this->store[] = $store;

        return $this;
    }

    /**
     * Remove store
     *
     * @param Store $store
     */
    public function removeStore(Store $store)
    {
        $this->store->removeElement($store);
    }

    /**
     * Get store
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getStore()
    {
        return $this->store;
    }

    /**
     * Add notification.
     *
     * @param Notification $notification
     *
     * @return User
     */
    public function addNotification(Notification $notification)
    {
        $this->notification[] = $notification;

        return $this;
    }

    /**
     * Remove notification.
     *
     * @param Notification $notification
     *
     * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removeNotification(Notification $notification)
    {
        return $this->notification->removeElement($notification);
    }

    /**
     * Get notification.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getNotification()
    {
        return $this->notification;
    }

    /**
     * Add command.
     *
     * @param Command $command
     *
     * @return User
     */
    public function addCommand(Command $command)
    {
        $this->command[] = $command;

        return $this;
    }

    /**
     * Remove command.
     *
     * @param Command $command
     *
     * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removeCommand(Command $command)
    {
        return $this->command->removeElement($command);
    }

    /**
     * Get command.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCommand()
    {
        return $this->command;
    }
}
