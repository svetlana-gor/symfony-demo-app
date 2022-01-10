<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Controller\Api;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

class ApiController
{
    #[Route('/api', name: 'resource_list')]
    public function getResourceList(Request $request): JsonResponse
    {
        $products = self::getAllProducts();
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

    #[Route('/api/product{_sku}', name: 'product_info')]
    public function getProductInfo(string $_sku, TranslatorInterface $translator): JsonResponse
    {
        $currentProduct = [];
        $products = self::getAllProducts();
        foreach ($products as $product) {
            if (mb_substr($product['sku'], -4) === $_sku) {
                $currentProduct[$translator->trans('name')] = $product['name'];
                $currentProduct[$translator->trans('description')] = $product['description'];
                $currentProduct[$translator->trans('sku')] = $product['sku'];
                break;
            }
        }

        $response = new JsonResponse($currentProduct);
        $response->setEncodingOptions(\JSON_UNESCAPED_UNICODE);

        return $response;
    }

    #[Route('/api/my-info', name: 'client_info')]
    public function getClientInfo(Request $request): JsonResponse
    {
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

    public static function getAllProducts(): array
    {
        $rows = array_map('str_getcsv', file('../data/products.csv'));
        $keys = array_shift($rows);
        $allProducts = [];
        foreach ($rows as $row) {
            $allProducts[] = array_combine($keys, $row);
        }

        return $allProducts;
    }
}
