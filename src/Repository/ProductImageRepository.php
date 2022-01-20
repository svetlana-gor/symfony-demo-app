<?php

namespace App\Repository;

use App\Entity\ProductImage;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\Tools\Pagination\Paginator;

/**
 * @method ProductImage|null find($id, $lockMode = null, $lockVersion = null)
 * @method ProductImage|null findOneBy(array $criteria, array $orderBy = null)
 * @method ProductImage[]    findAll()
 * @method ProductImage[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProductImageRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ProductImage::class);
    }

    /**
     * Gets a list of images and products that contain those images.
     *
     * @param int $page          Current page number.
     * @param int $imagesPerPage Number images per page.
     *
     * @return Paginator
     */
    public function getImagesWithProducts(int $page, int $imagesPerPage): Paginator
    {
        $entityManager = $this->getEntityManager();
        $query = $entityManager->createQuery(
            'SELECT i, p
            FROM App\Entity\ProductImage i
            JOIN i.products p
            ORDER BY i.title'
        );
        $query
            ->setMaxResults($imagesPerPage)
            ->setFirstResult($imagesPerPage * ($page - 1))
            ->getResult();

        return new Paginator($query);
    }

    // /**
    //  * @return ProductImage[] Returns an array of ProductImage objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?ProductImage
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
