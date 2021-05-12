<?php

namespace App\Entity;

use App\Repository\ReviewRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=ReviewRepository::class)
 */
class Review
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private ?int $id;

    /**
     * @ORM\Column(type="text")
     * @Assert\NotBlank(message="Tell us what you thought about the product")
     * @Assert\Length(min="20", minMessage="Please tell us a bit more!")
     */
    private ?string $content;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="reviews")
     * @ORM\JoinColumn(nullable=false)
     */
    private User $owner;

    /**
     * @ORM\ManyToOne(targetEntity=Product::class, inversedBy="reviews")
     * @ORM\JoinColumn(nullable=false)
     */
    private Product $product;

    /**
     * @ORM\Column(type="integer")
     * @Assert\GreaterThanOrEqual(1, message="It sounds terrible! But 1 is the minimum rating.")
     * @Assert\LessThanOrEqual(5, message="I'm glad you love it! But 5 is the max rating.")
     * @Assert\NotBlank(message="Please rate this!")
     */
    private ?int $stars;

    public function __construct(User $owner, Product $product)
    {
        $this->owner = $owner;
        $this->product = $product;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): self
    {
        $this->content = $content;

        return $this;
    }

    public function getOwner(): User
    {
        return $this->owner;
    }

    public function getProduct(): Product
    {
        return $this->product;
    }

    public function getStars(): ?int
    {
        return $this->stars;
    }

    public function setStars(int $stars): self
    {
        $this->stars = $stars;

        return $this;
    }
}
