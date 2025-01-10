<?php
namespace App\Service;

use DateTime;

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

    public function getBooksReading(int $userId): array
    {
        $booksRead = $this->bookReadRepository->findBy(['user_id' => $userId, 'is_read' => false]);
        $booksReadInProgress = [];
        foreach ($booksRead as $bookRead) {
            $book = $bookRead->getBook();
            $booksReadInProgress[] = [
                'name' => $book->getName(),
                'description' => $book->getDescription(),
                'updatedAt' => $bookRead->getUpdatedAt()
            ];
        }
        return $booksReadInProgress;
    }

    public function getBooksRead(int $userId): array
    {
        $booksRead = $this->bookReadRepository->findBy(['user_id' => $userId, 'is_read' => true]);
        $booksReadDone = [];
        foreach ($booksRead as $bookRead) {
            $book = $bookRead->getBook();
            $booksReadDone[] = [
                'name' => $book->getName(),
                'description' => $book->getDescription(),
                'category' => $book->getCategory()->getName(),
                'rating' => floor($bookRead->getRating())
            ];
        }
        return $booksReadDone;
    }

    public function addBookRead(int $userId, array $data): void
    {
        $newBookRead = new BookRead();
        $newBookRead->setUserId($userId);
        $newBookRead->setRead($data['is_read']);
        $newBookRead->setBook($data['book']);
        $newBookRead->setRating($data['rating']);
        $newBookRead->setDescription($data['description']);
        $newBookRead->setCreatedAt(new DateTime());
        $newBookRead->setUpdatedAt(new DateTime());
        $this->entityManager->persist($newBookRead);
        $this->entityManager->flush();
    }
}
