<?php

namespace App\EventListener;

use App\Entity\Product;
use Doctrine\Persistence\Event\LifecycleEventArgs;
use Symfony\Component\Security\Core\Security;

/**
 * Sets currently logged user as Product entity author.
 */
class UserSetter
{
    private Security $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    public function prePersist(Product $product, LifecycleEventArgs $event): void
    {
        $user = $this->security->getUser()->getId();
        $product->setAuthor($user);
    }
}
