<?php

namespace App\Controller\Api;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

class ApiSecondController extends AbstractController
{
    #[Route('/second_api', name: 'second_resource_list')]
    public function getResourceList(Request $request): JsonResponse
    {
        $products = ApiController::getAllProducts();
        $urls = [];
        $urls[] = $this->generateUrl('second_resource_list', [], UrlGeneratorInterface::ABSOLUTE_URL);
        $urls[] = $this->generateUrl('client_info', [], UrlGeneratorInterface::ABSOLUTE_URL);
        foreach ($products as $product) {
            $urls[] = $this->generateUrl('second_product_info', ['product' => $product['sku']], UrlGeneratorInterface::ABSOLUTE_URL);
        }

        $response = new JsonResponse($urls);
        $response->setEncodingOptions(JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);

        return $response;
    }

    #[Route('/second_api/{product}', name: 'second_product_info')]
    public function getProductInfo(string $product, TranslatorInterface $translator): JsonResponse
    {
        $currentProduct = [];
        $products = ApiController::getAllProducts();
        foreach ($products as $someProduct) {
            if ($someProduct['sku'] === $product) {
                $currentProduct[$translator->trans('name')] = $someProduct['name'];
                $currentProduct[$translator->trans('description')] = $someProduct['description'];
                $currentProduct[$translator->trans('sku')] = $someProduct['sku'];
                break;
            }
        }

        $response = new JsonResponse($currentProduct);
        $response->setEncodingOptions(\JSON_UNESCAPED_UNICODE);

        return $response;
    }
}
