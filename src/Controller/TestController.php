<?php

namespace App\Controller;

use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TestController extends AbstractController
{
    private $logger;

    public function __construct(LoggerInterface $logger) {
        $this->logger = $logger;
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
}