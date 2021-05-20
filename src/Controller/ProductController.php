<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Product;
use App\Entity\Review;
use App\Form\AddItemToCartFormType;
use App\Form\ReviewForm;
use App\Repository\CategoryRepository;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;

class ProductController extends AbstractController
{
    /**
     * @Route("/", name="app_homepage")
     * @Route("/category/{id}", name="app_category")
     */
    public function index(Request $request, CategoryRepository $categoryRepository, ProductRepository $productRepository, Category $category = null): Response
    {
        $searchTerm = $request->query->get('q');
        $products = $productRepository->search(
            $category,
            $searchTerm
        );

        if ($request->query->get('preview')) {
            return $this->render('product/_searchPreview.html.twig', [
                'products' => $products,
            ]);
        }

        return $this->render('product/index.html.twig', [
            'currentCategory' => $category,
            'categories' => $categoryRepository->findAll(),
            'products' => $products,
            'searchTerm' => $searchTerm
        ]);
    }

    /**
     * @Route("/product/{id}", name="app_product", methods={"GET"})
     */
    public function showProduct(Product $product, CategoryRepository $categoryRepository): Response
    {
        $addToCartForm = $this->createForm(AddItemToCartFormType::class, null, [
            'product' => $product
        ]);

        $reviewForm = null;
        if ($this->getUser()) {
            $reviewForm = $this->createForm(ReviewForm::class, new Review($this->getUser(), $product));
        }

        return $this->render('product/show.html.twig', [
            'product' => $product,
            'currentCategory' => $product->getCategory(),
            'categories' => $categoryRepository->findAll(),
            'addToCartForm' => $addToCartForm->createView(),
            'reviewForm' => $reviewForm ? $reviewForm->createView() : null,
        ]);
    }

    /**
     * @Route("/product/{id}/reviews", name="app_product_reviews")
     */
    public function productReviews(Product $product, CategoryRepository $categoryRepository, Request $request, EntityManagerInterface $entityManager)
    {
        $reviewForm = null;
        if ($this->getUser()) {
            $reviewForm = $this->createForm(ReviewForm::class, new Review($this->getUser(), $product));

            $reviewForm->handleRequest($request);

            if ($reviewForm->isSubmitted() && $reviewForm->isValid()) {
                $entityManager->persist($reviewForm->getData());
                $entityManager->flush();

                $this->addFlash('success', 'Thanks for your review! I like you!');

                return $this->redirectToRoute('app_product_reviews', [
                    'id' => $product->getId(),
                ]);
            }
        }

        return $this->render('product/reviews.html.twig', [
            'product' => $product,
            'currentCategory' => $product->getCategory(),
            'categories' => $categoryRepository->findAll(),
            'reviewForm' => $reviewForm ? $reviewForm->createView() : null,
        ]);
    }

    /**
     * @Route("/you-won")
     */
    public function iLikeCounting()
    {
        return $this->render('product/counting.html.twig');
    }
}
