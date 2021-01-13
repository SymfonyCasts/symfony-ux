<?php

namespace App\Service;

use App\Entity\Cart;
use App\Entity\CartItem;
use App\Repository\ColorRepository;
use App\Repository\ProductRepository;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class CartStorage
{
    private $session;
    private $productRepository;
    private $colorRepository;

    public function __construct(SessionInterface $session, ProductRepository $productRepository, ColorRepository  $colorRepository)
    {
        $this->session = $session;
        $this->productRepository = $productRepository;
        $this->colorRepository = $colorRepository;
    }

    public function getCart(): ?Cart
    {
        $key = self::getKey();
        if (!$this->session->has($key)) {
            return null;
        }
        $cart = $this->session->get($key);

        if (!$cart instanceof Cart) {
            throw new \InvalidArgumentException('Wrong cart type');
        }

        // create new so if we modify it, but don't want to save back, it's
        // not automatically modified in the session
        $newCart = new Cart();
        // refresh the Products from the database
        foreach ($cart->getItems() as $item) {
            $newCart->addItem(new CartItem(
                $this->productRepository->find($item->getProduct()),
                $item->getQuantity(),
                $item->getColor() ? $this->colorRepository->find($item->getColor()) : null
            ));
        }

        return $newCart;
    }

    public function getOrCreateCart(): Cart
    {
        return $this->getCart() ?: new Cart();
    }

    public function save(Cart $cart)
    {
        $this->session->set(self::getKey(), $cart);
    }

    public function clearCart()
    {
        $this->session->remove(self::getKey());
    }

    private static function getKey(): string
    {
        return sprintf('_cart_storage');
    }
}
