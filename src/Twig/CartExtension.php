<?php

namespace App\Twig;

use App\ApiPlatform\CartDataPersister;
use App\Entity\Cart;
use App\Entity\CartItem;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class CartExtension extends AbstractExtension
{
    private $session;

    public function __construct(SessionInterface $session)
    {
        $this->session = $session;
    }

    public function getFunctions()
    {
        return [
            new TwigFunction('count_cart_items', [$this, 'countCartItems']),
        ];
    }

    public function countCartItems(): int
    {
        $cartId = $this->session->get('_cart_id');
        if (!$cartId) {
            return 0;
        }

        $key = CartDataPersister::getKey($cartId);
        if (!$this->session->has($key)) {
            return 0;
        }

        /** @var Cart $cart */
        $cart = $this->session->get($key);

        return array_reduce($cart->getItems(), function($accumulator, CartItem $item) {
            return $accumulator + $item->getQuantity();
        }, 0);
    }
}
