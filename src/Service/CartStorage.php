<?php

namespace App\Service;

use App\Entity\Cart;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class CartStorage
{
    private $session;

    public function __construct(SessionInterface $session)
    {
        $this->session = $session;
    }

    public function getCart(): ?Cart
    {
        $key = self::getKey();
        if (!$this->session->has($key)) {
            return null;
        }

        // cloned so if we modify it, but don't want to save back, it's
        // not automatically modified in the session
        return clone $this->session->get($key);
    }

    public function getOrCreateCart(): Cart
    {
        return $this->getCart() ?: new Cart();
    }

    public function save(Cart $cart)
    {
        $this->session->set(self::getKey(), $cart);
    }

    public static function getKey(): string
    {
        return sprintf('_cart_storage');
    }
}
