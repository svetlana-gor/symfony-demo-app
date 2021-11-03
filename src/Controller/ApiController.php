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

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\UnsupportedMediaTypeHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\RouterInterface;

class ApiController
{
    private array $products;

    /**
     * @param Request         $request
     * @param RouterInterface $router
     *
     * @return Response
     */
    #[Route('/api', name: 'resource_list', ),]
    public function getResourceList(Request $request, RouterInterface $router): Response
    {
        if (!$request->headers->get('content-type')) {
            throw new UnsupportedMediaTypeHttpException('Your request must contain a Content-type header.');
        }

        $resources = [];
        $routes = $router->getRouteCollection()->all();
        foreach ($routes as $route) {
            if (str_contains($request->getUriForPath($route->getPath()), '/api')) {
                $resources[] = str_replace('{_locale}',
                    $request->get('_locale'),
                    $request->getUriForPath($route->getPath())
                );
            }
        }

        $productCount = \count($this->getAllProducts());
        foreach ($resources as $resource) {
            if (str_contains($resource, '{_sku}')) {
                for ($i = 0; $i < $productCount; ++$i) {
                    $resources[] = str_replace('{_sku}',
                        mb_substr($this->products[$i]['sku'], -4),
                        $resource
                    );
                }
                unset($resources[array_search($resource, $resources, true)]);
                $resources = array_values($resources);
            }
        }

        return new Response(json_encode($resources, \JSON_UNESCAPED_SLASHES));
    }

    /**
     * @param Request $request
     * @param string  $_sku
     *
     * @return Response
     */
    #[Route('/api/product{_sku}', name: 'product_info')]
    public function getProductInfo(Request $request, string $_sku): Response
    {
        if (!$request->headers->get('content-type')) {
            throw new UnsupportedMediaTypeHttpException('Your request must contain a Content-type header.');
        }

        $currentProduct = [];
        $this->getAllProducts();
        foreach ($this->products as $product) {
            if (mb_substr($product['sku'], -4) === $_sku) {
                $currentProduct = $product;
                break;
            }
        }

        return new Response(json_encode($currentProduct, \JSON_UNESCAPED_UNICODE));
    }

    /**
     * @param Request $request
     *
     * @return Response
     */
    #[Route('/api/my-info', name: 'client_info')]
    public function getClientInfo(Request $request): Response
    {
        if (!$request->headers->get('content-type')) {
            throw new UnsupportedMediaTypeHttpException('Your request must contain a Content-type header.');
        }

        $clientInfo = [];
        $clientInfo['ip'] = $_SERVER['REMOTE_ADDR'];
        $clientInfo['language'] = mb_substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 5);

        $browserInfo = get_browser(null, true);
        $clientInfo['browser'] = $browserInfo['browser'];

        $result = json_encode($clientInfo).\PHP_EOL;

        $handle = fopen('../public/resources.txt', 'a');
        fwrite($handle, $result);
        fclose($handle);

        return new Response($result);
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
