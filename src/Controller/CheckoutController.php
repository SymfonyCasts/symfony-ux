<?php

namespace App\Controller;


use App\Entity\Purchase;
use App\Form\AddItemToCartFormType;
use App\Form\CheckoutFormType;
use App\Repository\ProductRepository;
use App\Repository\PurchaseRepository;
use App\Service\CartStorage;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class CheckoutController extends AbstractController
{
    /**
     * @Route("/checkout", name="app_checkout")
     */
    public function checkout(ProductRepository $productRepository, Request $request, CartStorage $cartStorage, EntityManagerInterface $entityManager, SessionInterface $session): Response
    {
        $checkoutForm = $this->createForm(CheckoutFormType::class);
        $featuredProduct = $productRepository->findFeatured();
        $addToCartForm = $this->createForm(AddItemToCartFormType::class, null, [
            'product' => $featuredProduct,
        ]);

        $checkoutForm->handleRequest($request);
        if ($checkoutForm->isSubmitted() && $checkoutForm->isValid()) {
            /** @var Purchase $purchase */
            $purchase = $checkoutForm->getData();
            $purchase->addItemsFromCart($cartStorage->getCart());

            $entityManager->persist($purchase);
            $entityManager->flush();

            $session->set('purchase_id', $purchase->getId());
            $cartStorage->clearCart();

            return $this->redirectToRoute('app_confirmation');
        }

        return $this->render('checkout/checkout.html.twig', [
            'checkoutForm' => $checkoutForm->createView(),
            'featuredProduct' => $featuredProduct,
            'addToCartForm' => $addToCartForm->createView(),
        ]);
    }

    /**
     * @Route("/confirmation", name="app_confirmation")
     */
    public function confirmation(SessionInterface $session, PurchaseRepository $purchaseRepository): Response
    {
        $purchaseId = $session->get('purchase_id');
        $purchase = $purchaseRepository->find($purchaseId);

        if (!$purchase) {
            throw $this->createNotFoundException('No purchase found');
        }

        return $this->render('checkout/confirmation.html.twig', [
            'purchase' => $purchase,
        ]);
    }
}
