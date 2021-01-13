<?php

namespace App\Controller;


use App\Entity\Color;
use App\Entity\Product;
use App\Form\AddItemToCartFormType;
use App\Repository\CategoryRepository;
use App\Repository\ColorRepository;
use App\Repository\ProductRepository;
use App\Service\CartStorage;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Routing\Annotation\Route;

class CartController extends AbstractController
{
    /**
     * @Route("/cart", name="app_cart")
     */
    public function shoppingCart(CartStorage $cartStorage, ProductRepository  $productRepository): Response
    {
        $featuredProduct = $productRepository->findFeatured();
        $addToCartForm = $this->createForm(AddItemToCartFormType::class, null, [
            'product' => $featuredProduct,
        ]);

        return $this->render('cart/cart.html.twig', [
            'cart' => $cartStorage->getOrCreateCart(),
            'featuredProduct' => $featuredProduct,
            'addToCartForm' => $addToCartForm->createView()
        ]);
    }

    /**
     * @Route("/product/{id}", name="app_cart_add_item", methods={"POST"})
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

    /**
     * @Route("/cart/remove/{productId}/{colorId?}", name="app_cart_remove_item", methods={"POST"})
     */
    public function removeItemToCart($productId, $colorId, Request $request, CartStorage $cartStorage, ProductRepository $productRepository, ColorRepository $colorRepository)
    {
        /** @var Product|null $product */
        $product = $productRepository->find($productId);
        /** @var Color|null $color */
        $color = $colorId ? $colorRepository->find($colorId) : null;

        if (!$product) {
            $this->createNotFoundException();
        }

        if (!$this->isCsrfTokenValid('remove_item', $request->request->get('_token'))) {
            throw new BadRequestHttpException('Invalid CSRF token');
        }

        $cart = $cartStorage->getOrCreateCart();
        $cartItem = $cart->findItem($product, $color);
        if ($cartItem) {
            $cart->removeItem($cartItem);
        }
        $cartStorage->save($cart);

        $this->addFlash('success', 'Item removed!');

        return $this->redirectToRoute('app_cart');
    }
}
