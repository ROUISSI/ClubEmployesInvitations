<?php


namespace App\Service;


use App\Entity\User;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class UserService
{
    /**
     * @var ManagerRegistry
     */
    private $doctrine;
    /**
     * @var UserPasswordHasherInterface
     */
    private $encoder;


    /**
     * UserService constructor.
     * @param UserPasswordHasherInterface $encoder
     * @param ManagerRegistry $doctrine
     */
    public function __construct(UserPasswordHasherInterface $encoder, ManagerRegistry $doctrine)
    {
        $this->doctrine = $doctrine;
        $this->encoder = $encoder;
    }

    /**
     * @param $decodedData
     */
    public function register($decodedData)
    {
        $em = $this->doctrine->getManager();
        $email = $decodedData->email;
        $password = $decodedData->password;
        $firstName = $decodedData->first_name;
        $lastName = $decodedData->last_name;
        $user = new User();
        $user->setPassword($this->encoder->hashPassword($user, $password));
        $user->setEmail($email);
        $user->setFirstName($firstName);
        $user->setLastName($lastName);
        $em->persist($user);
        $em->flush();
    }

    /**
     * @param $item
     * @param UserInterface $user
     * @return mixed
     */
    public function serahcUserByItem($item, UserInterface $user)
    {
        $em = $this->doctrine->getManager();
        $users = $em->getRepository(User::class)->searchUser($item, $user);
        return $users;
    }
}
