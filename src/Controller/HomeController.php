<?php
namespace App\Controller;

use App\Service\BookService;
use App\Service\AuthService;
use App\Form\AddBookType;
use App\Form\LoginFormType;
use App\Form\RegisterFormType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    private BookService $bookService;
    private AuthService $authService;

    public function __construct(
        BookService $bookService,
        AuthService $authService
    ) {
        $this->bookService = $bookService;
        $this->authService = $authService;
    }

    #[Route('/', name: 'app.home')]
    public function index(Request $request): Response
    {
        $userId = 1;

        $addBookForm = $this->createForm(AddBookType::class);
        $addBookForm->handleRequest($request);
        if ($addBookForm->isSubmitted() && $addBookForm->isValid()) {
            $data = $addBookForm->getData();
            $this->bookService->addBookRead($userId, $data);
        }

        return $this->render('pages/home.html.twig', [
            'name' => 'Accueil',
            'booksReading' => $this->bookService->getBooksReading($userId),
            'booksRead' => $this->bookService->getBooksRead($userId),
            'addBookForm' => $addBookForm->createView()
        ]);
    }

    #[Route('/login', name: 'auth.login')]
    public function login(Request $request): Response
    {
        $loginForm = $this->createForm(LoginFormType::class);
        $loginForm->handleRequest($request);

        if ($loginForm->isSubmitted() && $loginForm->isValid()) {
            $data = $loginForm->getData();
            if ($this->authService->authenticateUser($data)) {
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
        $registerForm = $this->createForm(RegisterFormType::class);
        $registerForm->handleRequest($request);

        if ($registerForm->isSubmitted() && $registerForm->isValid()) {
            $data = $registerForm->getData();
            if ($this->authService->registerUser($data)) {
                return $this->redirectToRoute('auth.login');
            }
        }

        return $this->render('auth/register.html.twig', [
            'name' => 'Inscription',
            'form' => $registerForm->createView(),
        ]);
    }
}
