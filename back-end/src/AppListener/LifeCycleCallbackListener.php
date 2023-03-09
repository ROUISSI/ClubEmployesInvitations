<?php


namespace App\AppListener;




use App\Entity\Invitation;
use App\Entity\User;
use Doctrine\Persistence\Event\LifecycleEventArgs;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class LifeCycleCallbackListener
{
    private $tokenStorage;

    /**
     * LifeCycleCallbackListener constructor.
     * @param $tokenStorage
     */
    public function __construct(TokenStorageInterface $tokenStorage)
    {
        $this->tokenStorage = $tokenStorage;
    }


    /**
     * @param LifecycleEventArgs $args
     * Ecouteur de post loading des entités (déclarer dans services.yaml)
     * @return $this|void
     * @throws \Exception
     */
    public function postLoad(LifecycleEventArgs $args)
    {
        $entity = $args->getObject();
        $token = $this->tokenStorage->getToken();
        if($entity instanceof User and $token and $token->getUser()){
            $invitation = 0;
            $amInSenders = $this->imInSenders($token->getUser(), $entity, $invitation);
            $amInReceivers = $this->imInReceivers($token->getUser(), $entity, $invitation);
            $entity->setAmInSenders($amInSenders);
            $entity->setAmInRecievers($amInReceivers);
            $entity->setInvitationRequest($invitation);
        }
        return $this;
    }


    /**
     * @param UserInterface $getUser
     * @param User $entity
     * @return int
     */
    private function imInSenders(UserInterface $getUser, User $entity, &$invitation)
    {
        $amIn = 0;
        $getUserId = $getUser->getId();
        $receivedInvits = $entity->getReceivedInvitations()->getValues();
        $invitationReceived = array_filter($receivedInvits, function ($invitation) use($getUserId) {
            /** @var Invitation $invitation  */
            return ($invitation->getSender()->getId() == $getUserId);
        });
        if($invitationReceived and $invitationReceived[1] instanceof Invitation){
            $amIn = $invitationReceived[1]->getStatus()->getId();
            $invitation = $invitationReceived[1]->getId();
        }
        return $amIn;
    }


    /**
     * @param UserInterface $getUser
     * @param User $entity
     * @return int
     */
    private function imInReceivers(UserInterface $getUser, User $entity, &$invitation)
    {
        $amIn = 0;
        $getUserId = $getUser->getId();
        $sentInvits = $entity->getSentInvitations()->getValues();
        $invitationSent = array_filter($sentInvits, function ($invitation) use($getUserId) {
            /** @var Invitation $invitation  */
            return ($invitation->getReceiver()->getId() == $getUserId);
        });
        if($invitationSent and $invitationSent[1] instanceof Invitation){
            $amIn = $invitationSent[1]->getStatus()->getId();
            $invitation = $invitationSent[1]->getId();
        }
        return $amIn;
    }

}
