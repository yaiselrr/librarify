<?php

namespace App\Controller\Api;

use App\Entity\Book;
use App\Repository\BookRepository;
use Doctrine\ORM\EntityManagerInterface;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class BookController extends AbstractFOSRestController
{
    private $loggerInterface;
    private $entityManagerInterface;
    private $bookRepository;

    public function __construct(LoggerInterface $loggerInterface, EntityManagerInterface $entityManagerInterface, BookRepository $bookRepository)
    {
        $this->loggerInterface = $loggerInterface;
        $this->entityManagerInterface = $entityManagerInterface;
        $this->bookRepository = $bookRepository;
    }

    /**
     * 
     * @Rest\Get(path="/books")
     * @Rest\View(serializerGroups={"book"},serializerEnableMaxDepthChecks=true)
     */
    public function index()
    {
        return $this->bookRepository->findAll();
    }

    /**
     * 
     * @Rest\Get(path="/books/new")
     * @Rest\View(serializerGroups={"book"},serializerEnableMaxDepthChecks=true)
     */
    public function store(Request $request)
    {
        $book = new Book();
        $response = new JsonResponse();

        $title = $request->get('title');
        $image = $request->get('image', 'without image');

        if (empty(trim($title))) {
            $response->setData([
                'success' => false,
                'message' => 'Title is required.'
            ])->setStatusCode(400);

            return $response;
        }

        $book->setTitle($title);
        $book->setImage($image);

        $this->entityManagerInterface->persist($book);
        $this->entityManagerInterface->flush();

        $response->setData([
            'success' => true,
            'data' => [
                'id' => $book->getId(),
                'title' => $book->getTitle(),
                'image' => $book->getImage()
            ]
        ])->setStatusCode(201);

        return $response;
    }
}
