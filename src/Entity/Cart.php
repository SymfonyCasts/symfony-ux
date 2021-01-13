<?php

namespace App\Entity;

class Cart
{
    /**
     * @var CartItem[]
     */
    private $items = [];

    /**
     * @return CartItem[]|array
     */
    public function getItems(): array
    {
        return $this->items;
    }

    public function addItem(CartItem $cartItem)
    {
        foreach ($this->items as $item) {
            if ($cartItem->matches($item)) {
                $item->increaseQuantity($cartItem->getQuantity());

                return;
            }
        }

        $this->items[] = $cartItem;
    }

    public function removeItem(CartItem $cartItem)
    {
        foreach ($this->items as $key => $item) {
            if ($cartItem->matches($item)) {
                unset($this->items[$key]);
                $this->items = array_values($this->items);

                return;
            }
        }
    }

    public function getTotal(): int
    {
        return array_reduce($this->getItems(), function($accumulator, CartItem $item) {
            return $accumulator + $item->getTotal();
        }, 0);
    }

    public function getTotalString(): string
    {
        return (string) ($this->getTotal() / 100);
    }

    public function countTotalItems(): int
    {
        return array_reduce($this->getItems(), function($accumulator, CartItem $item) {
            return $accumulator + $item->getQuantity();
        }, 0);
    }
}
