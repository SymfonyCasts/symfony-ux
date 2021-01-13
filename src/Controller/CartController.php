<?php

namespace App\Controller;


use App\Entity\Product;
use App\Form\AddItemToCartFormType;
use App\Repository\CategoryRepository;
use App\Repository\ProductRepository;
use App\Service\CartStorage;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CartController extends AbstractController
{
    /**
     * @Route("/cart", name="app_cart")
     */
    public function shoppingCart(CartStorage $cartStorage, ProductRepository  $productRepository): Response
    {
        return $this->render('cart/cart.html.twig', [
            'cart' => $cartStorage->getOrCreateCart(),
            'featuredProduct' => $productRepository->findFeatured(),
        ]);
    }

    /**
     * @Route("/cart/add/{id}", name="app_cart_add_item", methods={"POST"})
     */
    public function addItemToCart(Product $product, Request $request, CategoryRepository $categoryRepository, CartStorage $cartStorage)
    {
        $addToCartForm = $this->createForm(AddItemToCartFormType::class, null, [
            'product' => $product
        ]);

        $addToCartForm->handleRequest($request);
        if ($addToCartForm->isSubmitted() && $addToCartForm->isValid()) {
            $cart = $cartStorage->getOrCreateCart();
            $cart->addItem($addToCartForm->getData());
            $cartStorage->save($cart);

            $this->addFlash('success', 'Item added!');

            return $this->redirectToRoute('app_product', [
                'id' => $product->getId(),
            ]);
        }

        return $this->render('product/show.html.twig', [
            'product' => $product,
            'categories' => $categoryRepository->findAll(),
            'addToCartForm' => $addToCartForm->createView()
        ]);
    }
}
