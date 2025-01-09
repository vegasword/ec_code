<?php
namespace App\Controller;

use App\Entity\User;
use App\Form\LoginFormType;
use App\Form\RegisterFormType;
use App\Repository\BookReadRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\UserAuthenticatorInterface;

class HomeController extends AbstractController
{
    private BookReadRepository $readBookRepository;
    private EntityManagerInterface $entityManager;
    private UserPasswordHasherInterface $passwordHasher;

    public function __construct(
        BookReadRepository $bookReadRepository,
        EntityManagerInterface $entityManager,
        UserAuthenticatorInterface $userAuthenticator,
        UserPasswordHasherInterface $passwordHasher,
        LoggerInterface $logger
    ) {
        $this->readBookRepository = $bookReadRepository;
        $this->entityManager = $entityManager;
        $this->passwordHasher = $passwordHasher;
    }

    #[Route('/', name: 'app.home')]
    public function index(): Response
    {
        $userId = 1;
        $booksRead = $this->readBookRepository->findByUserId($userId, false);

        return $this->render('pages/home.html.twig', [
            'booksRead' => $booksRead,
            'name' => 'Accueil',
        ]);
    }

    #[Route('/login', name: 'auth.login')]
    public function login(Request $request, UserRepository $userRepository): Response
    {
        $loginForm = $this->createForm(LoginFormType::class);
        $loginForm->handleRequest($request);

        if ($loginForm->isSubmitted() && $loginForm->isValid()) {
            $data = $loginForm->getData();
            $user = $userRepository->findOneBy(['email' => $data['email']]);
            if ($user && $this->passwordHasher->isPasswordValid($user, $data['password'])) {
                return $this->redirectToRoute('app.home');
            }
        }

        return $this->render('auth/login.html.twig', [
            'name' => 'Connexion',
            'form' => $loginForm->createView(),
        ]);
    }

    #[Route('/register', name: 'auth.register')]
    public function register(Request $request): Response
    {
        $user = new User();
        $registerForm = $this->createForm(RegisterFormType::class);
        $registerForm->handleRequest($request);

        if ($registerForm->isSubmitted() && $registerForm->isValid()) {
            $data = $registerForm->getData();
            $user->setEmail($data['email']);
            $user->setPassword($this->passwordHasher->hashPassword($user, $data['password']));
            $this->entityManager->persist($user);
            $this->entityManager->flush();
            return $this->redirectToRoute('app.home');
        }

        return $this->render('auth/register.html.twig', [
            'name' => 'Inscription',
            'form' => $registerForm->createView(),
        ]);
    }
}
