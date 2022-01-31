<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use function Symfony\Component\String\u;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
 * @ORM\Table(name="symfony_demo_comment")
 *
 * Defines the properties of the Comment entity to represent the blog comments.
 * See https://symfony.com/doc/current/doctrine.html#creating-an-entity-class
 *
 * Tip: if you have an existing database, you can generate these entity class automatically.
 * See https://symfony.com/doc/current/doctrine/reverse_engineering.html
 *
 * @author Ryan Weaver <weaverryan@gmail.com>
 * @author Javier Eguiluz <javier.eguiluz@gmail.com>
 */
class Comment
{
    /**
     * @var int
     *
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private int $id;

    /**
     * @var Post
     *
     * @ORM\ManyToOne(targetEntity="Post", inversedBy="comments")
     * @ORM\JoinColumn(nullable=false)
     */
    private Post $post;

    /**
     * @var string
     *
     * @ORM\Column(type="text")
     * @Assert\NotBlank(message="comment.blank")
     * @Assert\Length(
     *     min=5,
     *     minMessage="comment.too_short",
     *     max=10000,
     *     maxMessage="comment.too_long"
     * )
     */
    private string $content;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="datetime")
     */
    private \DateTime $publishedAt;

    /**
     * @var UserInterface
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\User")
     * @ORM\JoinColumn(nullable=false)
     */
    private UserInterface $author;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     * @Assert\NotBlank(
     *     message = "Name cannot be blank.",
     *     groups = {"not_logged"}
     * )
     * @Assert\Length(
     *     min = "3",
     *     minMessage = "Name must be at least three characters long.",
     *     groups = {"not_logged"}
     * )
     */
    private ?string $anonymousUser;

    public function __construct()
    {
        $this->publishedAt = new \DateTime();
    }

    /**
     * @Assert\IsTrue(message="comment.is_spam")
     */
    public function isLegitComment(): bool
    {
        $containsInvalidCharacters = null !== u($this->content)->indexOf('@');

        return !$containsInvalidCharacters;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): void
    {
        $this->content = $content;
    }

    public function getPublishedAt(): \DateTime
    {
        return $this->publishedAt;
    }

    public function setPublishedAt(\DateTime $publishedAt): void
    {
        $this->publishedAt = $publishedAt;
    }

    public function getAuthor(): ?UserInterface
    {
        return $this->author;
    }

    public function setAuthor(UserInterface $author): void
    {
        $this->author = $author;
    }

    public function getPost(): ?Post
    {
        return $this->post;
    }

    public function setPost(Post $post): void
    {
        $this->post = $post;
    }

    public function getAnonymousUser(): ?string
    {
        return $this->anonymousUser;
    }

    public function setAnonymousUser(?string $anonymousUser): self
    {
        $this->anonymousUser = $anonymousUser;

        return $this;
    }
}
