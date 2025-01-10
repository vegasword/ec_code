<?php

namespace App\Controller;

use App\Service\BookService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class BooksController extends AbstractController
{
    private BookService $bookService;

    public function __construct(BookService $bookService) {
        $this->bookService = $bookService;
    }

    #[Route('/books/read', name: 'app_books_read', methods: ['GET'])]
    public function getAllBooksReadToJson(): Response
    {
        return $this->json($this->bookService->getBooksRead(1));
    }

    #[Route('/books/categories', name: 'app_books_categories', methods: ['GET'])]
    public function getAllBooksCategories(): Response
    {
        return $this->json($this->bookService->getAllCategoriesNames());
    }
}
