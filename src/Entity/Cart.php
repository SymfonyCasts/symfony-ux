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
}
