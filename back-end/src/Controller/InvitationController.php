<?php

namespace App\Controller;

use App\Entity\Invitation;
use App\Service\InvitationService;
use App\Service\UserService;
use JMS\Serializer\SerializationContext;
use JMS\Serializer\SerializerBuilder;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/api", name="api_")
 */
class InvitationController extends AbstractController
{
    /**
     * @Route("/invitation/sent", name="app_invitation_sent")
     */
    public function allSent(InvitationService $invitationService): JsonResponse
    {
        $invitations = $invitationService->getSentInvitations($this->getUser());
        $serializer = SerializerBuilder::create()
            ->build();
        $data = $serializer->serialize($invitations, 'json', SerializationContext::create()->setGroups(array('sent_group')));
        return $this->json(json_decode($data));
    }

    /**
     * @Route("/invitation/received", name="app_invitation_received")
     */
    public function allReceived(InvitationService $invitationService): JsonResponse
    {
        $invitations = $invitationService->getReceivedInvitations($this->getUser());
        $serializer = SerializerBuilder::create()
            ->build();
        $data = $serializer->serialize($invitations, 'json', SerializationContext::create()->setGroups(array('receiver_group')));
        return $this->json(json_decode($data));
    }

    /**
     * @Route("/invitation/{id}/cancel", name="app_cancel_invitation")
     *
     */
    public function cancel(InvitationService $invitationService, $id): JsonResponse
    {
        $response = $invitationService->cancelInvitation($id, $this->getUser());
        if ($response) {
            return $this->json(array("message" => "ok"), 200);
        } else {
            throw new \Exception("Action interdite");
        }

    }

    /**
     * @Route("/invitation/{id}/accept", name="app_accept_invitation")
     *
     */
    public function accept(InvitationService $invitationService, $id): JsonResponse
    {
        $response = $invitationService->acceptInvitation($id, $this->getUser());
        if ($response) {
            return $this->json(array("message" => "ok"), 200);
        } else {
            throw new \Exception("Action interdite");
        }

    }
    /**
     * @Route("/invitation/{id}/refuse", name="app_refuse_invitation")
     *
     */
    public function refuse(InvitationService $invitationService, $id): JsonResponse
    {
        $response = $invitationService->refuseInvitation($id, $this->getUser());
        if ($response) {
            return $this->json(array("message" => "ok"), 200);
        } else {
            throw new \Exception("Action interdite");
        }

    }
    /**
     * @Route("/invitation/{id}/remove", name="app_remove_invitation")
     *
     */
    public function remove(InvitationService $invitationService, $id): JsonResponse
    {
        $response = $invitationService->removeInvitation($id, $this->getUser());
        if ($response) {
            return $this->json(array("message" => "ok"), 200);
        } else {
            throw new \Exception("Action interdite");
        }

    }
    /**
     * @Route("/invitation/{id}/send", name="app_send_invitation")
     *
     */
    public function send(InvitationService $invitationService, $id): JsonResponse
    {
        $response = $invitationService->sendInvitation($id, $this->getUser());
        if ($response) {
            return $this->json(array("message" => "ok"), 200);
        } else {
            throw new \Exception("Action interdite");
        }

    }
    /**
     * @Route("/invitation/{item}/search/user", name="app_serach_user_invitation")
     *
     */
    public function searchUsers(UserService $userService, $item): JsonResponse
    {
        $users = $userService->serahcUserByItem($item, $this->getUser());
        $serializer = SerializerBuilder::create()
            ->build();
        $data = $serializer->serialize($users, 'json', SerializationContext::create()->setGroups(array('api', 'search_user')));
        return $this->json(json_decode($data));

    }
}
