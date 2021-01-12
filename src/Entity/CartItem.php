<?php

namespace App\Entity;

use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

/**
 * @Assert\Callback("validateColor")
 */
class CartItem
{
    /**
     * @var Product
     */
    private $product;

    /**
     * @var Color|null
     */
    private $color;

    /**
     * @Assert\Type("number", message="Enter a valid number")
     * @Assert\GreaterThanOrEqual(1)
     * @var int
     */
    private $quantity = 1;

    public function __construct(Product $product)
    {
        $this->product = $product;
    }

    public function getProduct(): Product
    {
        return $this->product;
    }

    public function getColor(): ?Color
    {
        return $this->color;
    }

    public function setColor(Color $color)
    {
        $this->color = $color;
    }

    public function getQuantity(): int
    {
        return $this->quantity;
    }

    public function setQuantity(int $quantity)
    {
        $this->quantity = $quantity;
    }

    public function matches(CartItem $cartItem)
    {
        $thisKey = sprintf('%s_%s', $this->getProduct()->getId(), $this->getColor() ? $this->getColor()->getId() : 'no_color');
        $thatKey = sprintf('%s_%s', $cartItem->getProduct()->getId(), $cartItem->getColor() ? $cartItem->getColor()->getId() : 'no_color');

        return $thisKey === $thatKey;
    }

    public function validateColor(ExecutionContextInterface $context)
    {
        if (!$this->product->hasColors()) {
            return;
        }

        if (!$this->color) {
            $context
                ->buildViolation('Please select a color')
                ->atPath('color')
                ->addViolation();
        }
    }
}
