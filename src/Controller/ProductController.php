<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Product;
use App\Repository\CategoryRepository;
use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;

class ProductController extends AbstractController
{
    /**
     * @Route("/", name="app_homepage")
     */
    public function index(CategoryRepository $categoryRepository, ProductRepository $productRepository): Response
    {
        return $this->render('product/index.html.twig', [
            'categories' => $categoryRepository->findAll(),
            'products' => $productRepository->findAllMostPopular(),
        ]);
    }

    /**
     * @Route("/category/{id}", name="app_category")
     */
    public function showCategory(Category $category, CategoryRepository $categoryRepository): Response
    {
        return $this->render('product/index.html.twig', [
            'categories' => $categoryRepository->findAll(),
        ]);
    }

    /**
     * @Route("/product/{id}", name="app_product")
     */
    public function showProduct(Product $product, CategoryRepository $categoryRepository): Response
    {
        return $this->render('product/index.html.twig', [
            'categories' => $categoryRepository->findAll(),
        ]);
    }
}
