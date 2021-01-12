<?php

namespace App\Entity;

class CartItem
{
    private $product;

    private $color;

    private $quantity;

    public function __construct(Product $product, Color $color = null)
    {
        $this->product = $product;
        $this->color = $color;
    }

    public function getProduct(): Product
    {
        return $this->product;
    }

    public function getColor(): ?Color
    {
        return $this->color;
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
}
