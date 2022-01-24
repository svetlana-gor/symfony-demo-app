<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Knp\DoctrineBehaviors\Contract\Entity\TimestampableInterface;
use Knp\DoctrineBehaviors\Model\Timestampable\TimestampableTrait;
use Symfony\Component\Uid\Uuid;

/**
 * @ORM\Entity(repositoryClass=ProductRepository::class)
 * @ORM\Table(name="symfony_demo_product")
 */
class Product implements TimestampableInterface
{
    use TimestampableTrait;

    /**
     * @ORM\Id
     * @ORM\Column(type="uuid", unique=true)
     */
    private Uuid $id;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private string $title;

    /**
     * @ORM\ManyToOne(targetEntity=ProductImage::class, inversedBy="products")
     * @ORM\JoinColumn(nullable=false)
     */
    private ProductImage $productImage;

    public function __construct(string $title)
    {
        $this->id = Uuid::v4();
        $this->title = $title;
    }

    public function getId(): ?Uuid
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getProductImage(): ?ProductImage
    {
        return $this->productImage;
    }

    public function setProductImage(?ProductImage $productImage): self
    {
        $this->productImage = $productImage;

        return $this;
    }
}
