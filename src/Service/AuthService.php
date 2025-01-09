<?php
namespace App\Service;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AuthService
{
    private EntityManagerInterface $entityManager;
    private UserRepository $userRepository;
    private UserPasswordHasherInterface $passwordHasher;

    public function __construct(
        EntityManagerInterface $entityManager,
        UserRepository $userRepository,
        UserPasswordHasherInterface $passwordHasher
    ) {
        $this->entityManager = $entityManager;
        $this->userRepository = $userRepository;
        $this->passwordHasher = $passwordHasher;
    }

    public function registerUser(array $data): ?User
    {
        $user = new User();
        $user->setEmail($data['email']);
        if (strcmp($data['password'], $data['confirm']) == 0) {
            $user->setPassword($this->passwordHasher->hashPassword($user, $data['password']));
            $this->entityManager->persist($user);
            $this->entityManager->flush();
            return $user;
        }
        return null;
    }

    public function authenticateUser(array $data): ?User
    {
        $user = $this->userRepository->findOneBy(['email' => $data['email']]);
        if ($user && $this->passwordHasher->isPasswordValid($user, $data['password'])) {
            $this->entityManager->persist($user);
            $this->entityManager->flush();
            return $user;
        }
        return null;
    }
}

