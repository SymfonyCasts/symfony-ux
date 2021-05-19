<?php

namespace App\Controller;

use App\Entity\Review;
use App\Form\ReviewType;
use App\Repository\ReviewRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/review")
 */
class ReviewAdminController extends AbstractController
{
    /**
     * @Route("/", name="review_admin_index", methods={"GET"})
     */
    public function index(ReviewRepository $reviewRepository): Response
    {
        return $this->render('review_admin/index.html.twig', [
            'reviews' => $reviewRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="review_admin_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $review = new Review();
        $form = $this->createForm(ReviewType::class, $review);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($review);
            $entityManager->flush();

            return $this->redirectToRoute('review_admin_index');
        }

        return $this->render('review_admin/new.html.twig', [
            'review' => $review,
            'form' => $form->createView(),
        ], new Response(null, $form->isSubmitted() ? 422 : 200));
    }

    /**
     * @Route("/{id}/edit", name="review_admin_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Review $review): Response
    {
        $form = $this->createForm(ReviewType::class, $review);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('review_admin_index');
        }

        return $this->render('review_admin/edit.html.twig', [
            'review' => $review,
            'form' => $form->createView(),
        ], new Response(null, $form->isSubmitted() ? 422 : 200));
    }

    /**
     * @Route("/{id}", name="review_admin_delete", methods={"POST"})
     */
    public function delete(Request $request, Review $review): Response
    {
        if ($this->isCsrfTokenValid('delete'.$review->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($review);
            $entityManager->flush();
        }

        return $this->redirectToRoute('review_admin_index');
    }
}
