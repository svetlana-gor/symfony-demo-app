<?php

namespace App\Controller\Api;

use App\Entity\Product;
use App\Entity\ProductImage;
use App\Repository\ProductImageRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProductImageController extends AbstractController
{
    #[Route('api/product/images', name: 'product_images')]
    public function index(Request $request, ProductImageRepository $productImageRepository): Response
    {
        $page = $request->query->getInt('page', 1);
        $imagesPerPage = $request->query->getInt('images_per_page', 10);

        $images = iterator_to_array($productImageRepository->getImagesWithProducts($page, $imagesPerPage)->getIterator());

        $response = array_map(function(ProductImage $productImage) {
            return [
                'title' => $productImage->getTitle(),
                'products' => array_map(fn (Product $product) => $product->getTitle(), $productImage->getProducts()->toArray()),
            ];
        }, $images);

        return $this->json($response);
    }
}
