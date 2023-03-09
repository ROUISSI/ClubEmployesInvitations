<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Persistence\Event\LifecycleEventArgs;
use JMS\Serializer\Annotation as Serializer;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @ORM\HasLifecycleCallbacks
 */
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Serializer\Groups({"api", "sent_group", "receiver_group"})
     *
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     * @Serializer\Groups({"api"})
     * @Serializer\SerializedName("username")
     */
    private $email;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=255)
     * @Serializer\Groups({"api", "sent_group", "receiver_group"})
     * @Serializer\SerializedName("firstName")
     */
    private $firstName;

    /**
     * @ORM\Column(type="string", length=255)
     * @Serializer\Groups({"api", "sent_group", "receiver_group"})
     * @Serializer\SerializedName("lastName")
     *
     */
    private $lastName;

    /**
     * @var \Doctrine\Common\Collections\ArrayCollection
     * @ORM\OneToMany(targetEntity="App\Entity\Invitation", mappedBy="sender", cascade={"persist","remove"})
     */
    private $sentInvitations;


    /**
     * @var \Doctrine\Common\Collections\ArrayCollection
     * @ORM\OneToMany(targetEntity="App\Entity\Invitation", mappedBy="receiver", cascade={"persist","remove"})
     */
    private $receivedInvitations;


    /**
     * @var int
     * @Serializer\Groups({"search_user"})
     * @Serializer\SerializedName("am_in_receivers")
     */
    private $amInRecievers;


    /**
     * @var int
     * @Serializer\Groups({"search_user"})
     * @Serializer\SerializedName("am_in_senders")
     */
    private $amInSenders;

    /**
     * @var int
     * @Serializer\Groups({"search_user"})
     * @Serializer\SerializedName("invitation")
     */
    private $invitationRequest;

    /**
     * User constructor.
     */
    public function __construct()
    {
        $this->sentInvitations = new ArrayCollection();
        $this->receivedInvitations = new ArrayCollection();
    }


    /**
     * @Serializer\Expose()
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string|null
     */
    public function getEmail(): ?string
    {
        return $this->email;
    }


    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string)$this->email;
    }

    /**
     * @deprecated since Symfony 5.3, use getUserIdentifier instead
     */
    public function getUsername(): string
    {
        return (string)$this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Returning a salt is only needed, if you are not using a modern
     * hashing algorithm (e.g. bcrypt or sodium) in your security.yaml.
     *
     * @see UserInterface
     */
    public function getSalt(): ?string
    {
        return null;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): self
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): self
    {
        $this->lastName = $lastName;

        return $this;
    }


    /**
     * @return ArrayCollection
     */
    public function getSentInvitations()
    {
        return $this->sentInvitations;
    }

    /**
     * @param Invitation $sentInvitation
     * @return bool
     */
    public function hasSentInvitation(Invitation $sentInvitation)
    {
        return $this->sentInvitations->contains($sentInvitation);
    }

    /**
     * @param Invitation $sentInvitation
     * @return $this
     */
    public function addSentInvitation(Invitation $sentInvitation)
    {
        if (!$this->sentInvitations->contains($sentInvitation)) {
            $this->sentInvitations->add($sentInvitation);
        }

        return $this;
    }

    /**
     * @param Invitation $sentInvitation
     * @return $this
     */
    public function removeSentInvitation(Invitation $sentInvitation)
    {
        if ($this->sentInvitations->contains($sentInvitation)) {
            $this->sentInvitations->removeElement($sentInvitation);
        }

        return $this;
    }

    /**
     * @return ArrayCollection
     */
    public function getReceivedInvitations()
    {
        return $this->receivedInvitations;
    }

    /**
     * @param Invitation $receivedInvitation
     * @return bool
     */
    public function hasReceivedInvitation(Invitation $receivedInvitation)
    {
        return $this->receivedInvitations->contains($receivedInvitation);
    }

    /**
     * @param Invitation $receivedInvitation
     * @return $this
     */
    public function addReceivedInvitation(Invitation $receivedInvitation)
    {
        if (!$this->receivedInvitations->contains($receivedInvitation)) {
            $this->receivedInvitations->add($receivedInvitation);
        }

        return $this;
    }

    /**
     * @param Invitation $receivedInvitation
     * @return $this
     */
    public function removeReceivedInvitation(Invitation $receivedInvitation)
    {
        if ($this->receivedInvitations->contains($receivedInvitation)) {
            $this->receivedInvitations->removeElement($receivedInvitation);
        }
        return $this;
    }

    /**
     * @return int
     */
    public function getAmInRecievers()
    {
        return $this->amInRecievers;
    }

    /**
     * @param int $amInRecievers
     */
    public function setAmInRecievers($amInRecievers)
    {
        $this->amInRecievers = $amInRecievers;
    }

    /**
     * @return int
     */
    public function getAmInSenders()
    {
        return $this->amInSenders;
    }

    /**
     * @param int $amInSenders
     */
    public function setAmInSenders($amInSenders)
    {
        $this->amInSenders = $amInSenders;
    }

    public function setInvitationRequest($invitation)
    {
        $this->invitationRequest = $invitation;
    }


}
