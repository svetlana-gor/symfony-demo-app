<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\UnsupportedMediaTypeHttpException;
use Symfony\Component\Routing\Annotation\Route;

class ApiController
{
    private array $products;

    /**
     * @param Request $request
     *
     * @return JsonResponse
     */
    #[Route('/api', name: 'resource_list')]
    public function getResourceList(Request $request): JsonResponse
    {
        $this->isRequestContentTypeJson($request);

        $products = $this->getAllProducts();
        function generateList($request, $products): \Generator
        {
            yield $request->getUri();
            yield $request->getUri().'/my-info';
            foreach ($products as $product) {
                yield $request->getUri().'/product_'.mb_substr($product['sku'], -3);
            }
        }

        $result = iterator_to_array(generateList($request, $products));
        $response = new JsonResponse($result);
        $response->setEncodingOptions(\JSON_UNESCAPED_SLASHES);

        return $response;
    }

    /**
     * @param Request $request
     * @param string  $_sku
     *
     * @return JsonResponse
     */
    #[Route('/api/product{_sku}', name: 'product_info')]
    public function getProductInfo(Request $request, string $_sku): JsonResponse
    {
        $this->isRequestContentTypeJson($request);

        $currentProduct = [];
        $this->getAllProducts();
        foreach ($this->products as $product) {
            if (mb_substr($product['sku'], -4) === $_sku) {
                $currentProduct = $product;
                break;
            }
        }

        $response = new JsonResponse($currentProduct);
        $response->setEncodingOptions(\JSON_UNESCAPED_UNICODE);

        return $response;
    }

    /**
     * @param Request $request
     *
     * @return JsonResponse
     */
    #[Route('/api/my-info', name: 'client_info')]
    public function getClientInfo(Request $request): JsonResponse
    {
        $this->isRequestContentTypeJson($request);

        $clientInfo = [];
        $clientInfo['ip'] = $request->getClientIp();
        $clientInfo['language'] = $request->getPreferredLanguage();

        $browserInfo = get_browser(null, true);
        $clientInfo['browser'] = $browserInfo['browser'];

        $response = new JsonResponse($clientInfo);
        $response->setEncodingOptions(\JSON_UNESCAPED_UNICODE);

        $handle = fopen('../public/resources.txt', 'a');
        fwrite($handle, $response->getContent().\PHP_EOL);
        fclose($handle);

        return $response;
    }

    /**
     * @throws UnsupportedMediaTypeHttpException
     */
    private function isRequestContentTypeJson(Request $request)
    {
        if (!('application/json' === $request->headers->get('content-type'))) {
            throw new UnsupportedMediaTypeHttpException("Your request must contain a Content-type header with the value 'application/json'.");
        }
    }

    private function getAllProducts(): array
    {
        $rows = array_map('str_getcsv', file('../data/products.csv'));
        $keys = array_shift($rows);
        $allProducts = [];
        foreach ($rows as $row) {
            $allProducts[] = array_combine($keys, $row);
        }

        return $this->products = $allProducts;
    }
}
