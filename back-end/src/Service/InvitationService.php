<?php


namespace App\Service;


use App\Entity\Invitation;
use App\Entity\Status;
use App\Entity\User;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class InvitationService
{
    /**
     * @var ManagerRegistry
     */
    private $doctrine;


    /**
     * UserService constructor.
     * @param ManagerRegistry $doctrine
     */
    public function __construct(ManagerRegistry $doctrine)
    {
        $this->doctrine = $doctrine;
    }


    /**
     * @param User $user
     * @return array|mixed[]
     */
    public function getSentInvitations(UserInterface $user)
    {
        // Return all invitations for the sender
        return $user->getSentInvitations()->getValues();
    }

    /**
     * @param User $user
     * @return array|mixed[]
     */
    public function getReceivedInvitations(UserInterface $user)
    {
        $em = $this->doctrine->getManager();
        // Return only waiting and accepted invitations for the receiver
        $invitations = $em->getRepository(Invitation::class)->findBy(array('receiver' => $user, 'status' => array(Status::WAITING, Status::ACCEPTED)));
        return $invitations;
    }

    /**
     * @param Invitation $invitation
     * @param UserInterface $user
     * @return false
     */
    public function cancelInvitation($id, UserInterface $user)
    {
        $em = $this->doctrine->getManager();
        $invitation = $em->getRepository(Invitation::class)->find($id);
        $ok = false;
        if ($invitation and $invitation->getSender()->getId() == $user->getId() and $invitation->getStatus()->getId() == Status::WAITING) {
            $em->remove($invitation);
            $em->flush();
            $ok = true;
        }
        return $ok;
    }

    public function acceptInvitation($id, ?UserInterface $user)
    {
        $em = $this->doctrine->getManager();
        $invitation = $em->getRepository(Invitation::class)->find($id);
        $ok = false;
        if ($invitation and $invitation->getReceiver()->getId() == $user->getId() and $invitation->getStatus()->getId() == Status::WAITING) {
            $acceptStatus = $em->getRepository(Status::class)->find(Status::ACCEPTED);
            $invitation->setStatus($acceptStatus);
            $em->persist($invitation);
            $em->flush();
            $ok = true;
        }
        return $ok;
    }

    public function refuseInvitation($id, ?UserInterface $user)
    {
        $em = $this->doctrine->getManager();
        $invitation = $em->getRepository(Invitation::class)->find($id);
        $ok = false;
        if ($invitation and $invitation->getReceiver()->getId() == $user->getId() and $invitation->getStatus()->getId() == Status::WAITING) {
            $refuseStatus = $em->getRepository(Status::class)->find(Status::REFUSED);
            $invitation->setStatus($refuseStatus);
            $em->persist($invitation);
            $em->flush();
            $ok = true;
        }
        return $ok;
    }

    public function removeInvitation($id, ?UserInterface $user)
    {
        $em = $this->doctrine->getManager();
        $invitation = $em->getRepository(Invitation::class)->find($id);
        $ok = false;
        if ($invitation and in_array($user->getId(), array($invitation->getReceiver()->getId(), $invitation->getSender()->getId())) and $invitation->getStatus()->getId() == Status::ACCEPTED) {
            $em->remove($invitation);
            $em->flush();
            $ok = true;
        }
        return $ok;
    }

    public function sendInvitation($id, ?UserInterface $user)
    {
        $em = $this->doctrine->getManager();
        $receiver = $em->getRepository(User::class)->find($id);
        $ok = false;
        if ($receiver and $receiver->getId() != $user->getId()) {
            $invitationAlreadySent = $em->getRepository(Invitation::class)->findOneBy(array("sender" => $user, "receiver" => $receiver));
            $invitationAlreadyReceived = $em->getRepository(Invitation::class)->findOneBy(array("receiver" => $user, "sender" => $receiver));
            if (
                (empty($invitationAlreadyReceived) and empty($invitationAlreadySent))
                or ($invitationAlreadySent and $invitationAlreadySent->getStatus()->getId() == Status::REFUSED)

            ) {
                $invitation = $invitationAlreadySent;
                if (empty($invitation)) {
                    $invitation = new Invitation();
                    $invitation->setReceiver($receiver);
                    $invitation->setSender($user);
                }
                $waitingStatus = $em->getRepository(Status::class)->find(Status::WAITING);
                $invitation->setStatus($waitingStatus);
                $em->persist($invitation);
                $em->flush();
                $ok = true;
            }

        }
        return $ok;
    }
}
