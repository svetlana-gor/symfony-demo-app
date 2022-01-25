<?php

namespace App\Controller\Api;

use App\Service\Creation\ProductCreation;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ApiCreateProductController extends AbstractController
{
    #[Route('/api/product', name: 'api_create_product', methods: ['POST'])]
    public function createProduct(ProductCreation $productCreation, ManagerRegistry $doctrine): Response
    {
        $product = $productCreation::createProduct($doctrine);

        return new Response($product->getTitle() . ' successfully created.');
    }

    #[Route('/api', name: 'api')]
    public function api(): Response
    {
        return new Response('This is Api Main Page.');
    }
}
