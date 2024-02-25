<?php

namespace App\Controller;

use App\Entity\Book;
use App\Repository\BookRepository;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Routing\Annotation\Route;

class TestController extends AbstractController
{
    private $logger;
    private $em;
    private $bookRepository;

    public function __construct(LoggerInterface $logger, EntityManagerInterface $em, BookRepository $bookRepository) {
        $this->logger = $logger;
        $this->em = $em;
        $this->bookRepository = $bookRepository;
    }

    /**
     * 
     * @Route("/library/list", name="library_list")
     */
    public function list(Request $request)
    {
        // $response = new Response();

        // $response->setContent('<div>hola desde el controlador</div>');

        $this->logger->info('list create action');

        $title = $request->get('title');

        $response = new JsonResponse();

        $response->setData([
            'status' => 'success',
            [
                [
                    "id" =>  1,
                    "title" => "El arte de la guerra"
                ],
                [
                    "id" =>  2,
                    "title" => "El señor de los anillos"
                ],
                [
                    "id" =>  3, 
                    "title" => "El libro de la selva"
                ],
                [
                    "id" =>  4,
                    "title" => $title ?: "No hay título especificado"
                ],
            ]
        ]);

        return $response;
    }

    /**
     * @Route("/books", name="book_list")
     */
    public function index()
    {
        $response = new JsonResponse();
        $books = $this->bookRepository->findAll();
        $arrayResult = [];

        foreach ($books as $key => $value) {
            $arrayResult[] = [
                'id' => $value->getId(),
                'title' => $value->getTitle(),
                'image' => $value->getImage(),
            ];
        }

        $response->setData([
            'success' => true,
            'data' => $arrayResult
        ]);

        return $response;
    }

    /**
     * @Route("/books/create", name="book_create")
     */
    public function store(Request $request)
    {
        $title = $request->get('title');
        
        if (!$title) {
            throw new BadRequestHttpException("Missing title parameter");
        }
        
        $book = new Book();

        $book->setTitle($title);
        // $book->setTitle('prueba de title');

        $this->em->persist($book);
        $this->em->flush();

        $response = new JsonResponse();

        $response->setData([
            'success' => true,
            'book' => [
                'id' => $book->getId(),
                'title' => $book->getTitle(),
                'image' => $book->getImage(),
            ]

        ]);

        return $response;        
    }
}