<?php

namespace App\Controller;

use App\Entity\PromotionCode;
use App\Form\PromotionCodeType;
use App\Repository\PromotionCodeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/promotion/code")
 */
class PromotionCodeController extends AbstractController
{
    /**
     * @Route("/", name="promotion_code_index", methods={"GET"})
     */
    public function index(PromotionCodeRepository $promotionCodeRepository): Response
    {
        return $this->render('promotion_code/index.html.twig', [
            'promotion_codes' => $promotionCodeRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="promotion_code_new", methods={"GET", "POST"})
     */
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $promotionCode = new PromotionCode();
        $form = $this->createForm(PromotionCodeType::class, $promotionCode);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($promotionCode);
            $entityManager->flush();

            return $this->redirectToRoute('promotion_code_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('promotion_code/new.html.twig', [
            'promotion_code' => $promotionCode,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="promotion_code_show", methods={"GET"})
     */
    public function show(PromotionCode $promotionCode): Response
    {
        return $this->render('promotion_code/show.html.twig', [
            'promotion_code' => $promotionCode,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="promotion_code_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, PromotionCode $promotionCode, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(PromotionCodeType::class, $promotionCode);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('promotion_code_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('promotion_code/edit.html.twig', [
            'promotion_code' => $promotionCode,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="promotion_code_delete", methods={"POST"})
     */
    public function delete(Request $request, PromotionCode $promotionCode, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$promotionCode->getId(), $request->request->get('_token'))) {
            $entityManager->remove($promotionCode);
            $entityManager->flush();
        }

        return $this->redirectToRoute('promotion_code_index', [], Response::HTTP_SEE_OTHER);
    }
}
