<?php

namespace App\Controller;

use App\Service\UserService;
use Doctrine\Bundle\DoctrineBundle\Registry;
use Doctrine\Persistence\ManagerRegistry;
use JMS\Serializer\Expression\ExpressionEvaluator;
use JMS\Serializer\SerializerBuilder;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\ExpressionLanguage\ExpressionLanguage;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\User;
use JMS\Serializer\SerializationContext;

/**
 * @Route("/api", name="api_")
 */

class RegistrationController extends AbstractController
{
    /**
     * @Route("/register", name="register", methods={"POST"})
     */
    public function index(Request $request, UserService $userService): Response
    {
        $decodedData = json_decode($request->getContent());
        $userService->register($decodedData);
        return $this->json(['message' => 'Registered Successfully']);
    }

    /**
     * @Route("/account", name="user_account", methods={"GET"})
     */
    public function userInfos(): Response
    {
        $user = $this->getUser();
        $serializer = SerializerBuilder::create()
            ->build();
        $data = $serializer->serialize($user, 'json', SerializationContext::create()->setGroups(array('api')));
        return $this->json(json_decode($data));
    }
}
