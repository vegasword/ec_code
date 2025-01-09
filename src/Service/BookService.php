<?php
namespace App\Service;

use App\Entity\BookRead;
use App\Repository\BookReadRepository;
use App\Repository\BookRepository;
use Doctrine\ORM\EntityManagerInterface;

class BookService
{
    private EntityManagerInterface $entityManager;
    private BookRepository $bookRepository;
    private BookReadRepository $bookReadRepository;

    public function __construct(
        EntityManagerInterface $entityManager,
        BookRepository $bookRepository,
        BookReadRepository $bookReadRepository
    ) {
        $this->entityManager = $entityManager;
        $this->bookRepository = $bookRepository;
        $this->bookReadRepository = $bookReadRepository;
    }

    public function getBooksReadMetaData(int $userId): array
    {
        $booksRead = $this->bookReadRepository->findByUserId($userId, false);
        $booksReadMetaData = [];
        foreach ($booksRead as $bookRead) {
            $book = $this->bookRepository->findOneById($bookRead->getBookId());
            $booksReadMetaData[] = [
                'name' => $book->getName(),
                'description' => $book->getDescription(),
                'updatedAt' => $book->getUpdatedAt()
            ];
        }
        return $booksReadMetaData;
    }

    public function addBookRead(int $userId, array $data): void
    {
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
}

