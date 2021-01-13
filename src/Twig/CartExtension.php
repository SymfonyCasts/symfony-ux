<?php

namespace App\Twig;

use App\ApiPlatform\CartDataPersister;
use App\Entity\Cart;
use App\Entity\CartItem;
use App\Service\CartStorage;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class CartExtension extends AbstractExtension
{
    private $cartStorage;

    public function __construct(CartStorage $cartStorage)
    {
        $this->cartStorage = $cartStorage;
    }

    public function getFunctions()
    {
        return [
            new TwigFunction('count_cart_items', [$this, 'countCartItems']),
        ];
    }

    public function countCartItems(): int
    {
        $cart = $this->cartStorage->getCart();

        if (!$cart) {
            return 0;
        }

        return $cart->countTotalItems();
    }
}
