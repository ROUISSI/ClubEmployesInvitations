<?php

namespace App\Entity;

use App\Repository\InvitationRepository;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;


/**
 * @ORM\Entity(repositoryClass=InvitationRepository::class)
 * @ORM\HasLifecycleCallbacks
 */
class Invitation
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Serializer\Groups({"receiver_group", "sent_group"})
     */
    private $id;

    /**
     * @ORM\Column(type="datetime")
     * @Serializer\Groups({"receiver_group", "sent_group"})
     */
    private $createdAt;

    /**
     * @var Status
     * @ORM\ManyToOne(targetEntity="App\Entity\Status")
     * @ORM\JoinColumn(name="status_id", referencedColumnName="id", nullable=false)
     * @Serializer\Groups({"receiver_group", "sent_group"})
     */
    private $status;

    /**
     * @var User
     * @ORM\ManyToOne(targetEntity="User", inversedBy="sentInvitations")
     * @ORM\JoinColumn(name="sender", referencedColumnName="id", nullable=true)
     * @Serializer\Groups({"receiver_group"})
     *
     */
    private $sender;

    /**
     * @var User
     * @ORM\ManyToOne(targetEntity="User", inversedBy="invitationsReceived")
     * @ORM\JoinColumn(name="receiver", referencedColumnName="id", nullable=true)
     * @Serializer\Groups({"sent_group"})
     */
    private $receiver;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * @return Status
     */
    public function getStatus()
    {
        return $this->status;
    }

    public function setStatus(Status $status): self
    {
        $this->status = $status;

        return $this;
    }

    /**
     * @return User
     */
    public function getSender()
    {
        return $this->sender;
    }

    public function setSender(User $sender): self
    {
        $this->sender = $sender;

        return $this;
    }

    /**
     * @return User
     */
    public function getReceiver()
    {
        return $this->receiver;
    }

    public function setReceiver(User $receiver): self
    {
        $this->receiver = $receiver;

        return $this;
    }

    /**
     * @ORM\PrePersist
     * @return $this
     */
    public function prePersist()
    {
        $this->createdAt = new \DateTime();

        return $this;
    }


}
