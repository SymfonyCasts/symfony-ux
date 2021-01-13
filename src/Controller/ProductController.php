<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Product;
use App\Form\AddItemToCartFormType;
use App\Repository\CategoryRepository;
use App\Repository\ProductRepository;
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

        return $this->render('product/show.html.twig', [
            'product' => $product,
            'currentCategory' => $product->getCategory(),
            'categories' => $categoryRepository->findAll(),
            'addToCartForm' => $addToCartForm->createView()
        ]);
    }
}
