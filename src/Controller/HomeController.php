<?php
namespace App\Controller;

use App\Entity\Book;
use App\Entity\BookRead;
use App\Entity\User;
use App\Form\AddBookType;
use App\Form\LoginFormType;
use App\Form\RegisterFormType;
use App\Repository\BookReadRepository;
use App\Repository\BookRepository;
use App\Repository\UserRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    private EntityManagerInterface $entityManager;
    private UserPasswordHasherInterface $passwordHasher;
    private UserRepository $userRepository;
    private BookRepository $bookRepository;
    private BookReadRepository $bookReadRepository;
    
    public function __construct(
        EntityManagerInterface $entityManager,
        UserRepository $userRepository,
        UserPasswordHasherInterface $passwordHasher,
        BookRepository $bookRepository,
        BookReadRepository $bookReadRepository
    ) {
        $this->entityManager = $entityManager;
        $this->passwordHasher = $passwordHasher;
        $this->userRepository = $userRepository;
        $this->bookRepository = $bookRepository;
        $this->bookReadRepository = $bookReadRepository;
    }

    #[Route('/', name: 'app.home')]
    public function index(Request $request): Response
    {
        $userId = 1;

        // Read book display
        $booksRead = $this->bookReadRepository->findByUserId($userId, false);
        $booksReadMetaData = [];
        foreach ($booksRead as $bookRead)
        {
            $book = $this->bookRepository->findOneById($bookRead->getBookId());
            array_push($booksReadMetaData, [
                'name' => $book->getName(),
                'description' => $book->getDescription(),
                'updatedAt' => $book->getUpdatedAt()
            ]);
        }

        // Add book modal form handling
        $addBookForm = $this->createForm(AddBookType::class);
        $addBookForm->handleRequest($request);
        if ($addBookForm->isSubmitted() && $addBookForm->isValid())
        {
            $data = $addBookForm->getData();
            $selectedBook = $data['book'];
            $newBook = new BookRead();
            $newBook->setUserId($userId);
            $newBook->setRead($data['is_read']);
            $newBook->setBookId($selectedBook->getId());
            $newBook->setRating($data['rating']);
            $newBook->setDescription($data['description']);
            $newBook->setCreatedAt($selectedBook->getCreatedAt());
            $newBook->setUpdatedAt($selectedBook->getUpdatedAt());
            $this->entityManager->persist($newBook);
            $this->entityManager->flush();
        }

        return $this->render('pages/home.html.twig', [
            'name' => 'Accueil',
            'booksReadMetaData' => $booksReadMetaData,
            'addBookForm' => $addBookForm
        ]);
    }

    #[Route('/login', name: 'auth.login')]
    public function login(Request $request): Response
    {
        $loginForm = $this->createForm(LoginFormType::class);
        $loginForm->handleRequest($request);

        if ($loginForm->isSubmitted() && $loginForm->isValid()) {
            $data = $loginForm->getData();
            $user = $this->userRepository->findOneBy(['email' => $data['email']]);
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
