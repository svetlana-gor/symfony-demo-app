<?php

namespace App\Service\Creation;

use App\Entity\Product;
use App\Entity\ProductImage;
use Doctrine\Persistence\ManagerRegistry;

/**
 * Creates Product with related ProductImage.
 */
class ProductCreation
{
    public static function createProduct(ManagerRegistry $doctrine): Product
    {
        $entityManager = $doctrine->getManager();

        $productImage = new ProductImage('Product_Image_' . self::getNumber());
        $entityManager->persist($productImage);

        $product = new Product('Product_' . self::getNumber());
        $product->setProductImage($productImage);
        $entityManager->persist($product);

        $entityManager->flush();

        return $product;
    }

    /**
     * Gets random number to concatenate with the Product and ProductImage titles.
     *
     * @return string
     */
    private static function getNumber(): string
    {
        return rand();
    }
}
