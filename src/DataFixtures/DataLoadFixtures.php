<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Persistence\ObjectManager;
use App\Entity\ProductImage;
use App\Entity\Product;

/**
 * Creates fixtures for the Product and ProductImage entities.
 */
class DataLoadFixtures extends Fixture implements FixtureGroupInterface
{
    public static function getGroups(): array
    {
        return ['DataLoadGroup'];
    }

    public function load(ObjectManager $manager): void
    {
        $this->loadProducts($manager);
    }

    /**
     * Load Product and ProductImage objects with the passed EntityManager
     *
     * @param ObjectManager $manager
     */
    private function loadProducts(ObjectManager $manager): void
    {
        for ($i = 0; $i < 100; $i++) {
            $productImage = new ProductImage('Product_Image_' . $this->getNumber());

            for ($k = 0; $k < 5; $k++) {
                $product = new Product('Product_' . $this->getNumber());
                $product->setProductImage($productImage);

                $manager->persist($product);
            }
            $manager->persist($productImage);
        }
        $manager->flush();
    }

    /**
     * Gets random number to concatenate with the Product and ProductImage titles.
     *
     * @return string
     */
    private function getNumber(): string
    {
        return rand();
    }
}
