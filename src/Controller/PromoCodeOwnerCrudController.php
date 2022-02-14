<?php

namespace App\Controller;

use App\Entity\PromoCodeOwner;
use App\Form\PromoCodeOwnerType;
use App\Repository\PromoCodeOwnerRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/promo/code/owner")
 */
class PromoCodeOwnerCrudController extends AbstractController
{
    # /**
    # * @Route("/", name="promo_code_owner_crud_index", methods={"GET"})
    #  */
    #   public function index(PromoCodeOwnerRepository $promoCodeOwnerRepository): Response
    # {
    #    return $this->render('promo_code_owner_crud/index.html.twig', [
    #        'promo_code_owners' => $promoCodeOwnerRepository->findAll(),
    #     ]);
    # }

    /**
     * @Route("/", name="promo_code_owner_crud_new", methods={"GET", "POST"})
     */
    public function new(Request $request, EntityManagerInterface $entityManager,PromoCodeOwnerRepository $promoCodeOwnerRepository): Response
    {
        $promoCodeOwner = new PromoCodeOwner();
        $form = $this->createForm(PromoCodeOwnerType::class, $promoCodeOwner);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($promoCodeOwner);
            $entityManager->flush();

            return $this->redirectToRoute('promo_code_owner_crud_new', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('promo_code_owner_crud/new.html.twig', [
            'promo_code_owner' => $promoCodeOwner,
            'form' => $form->createView(),
            'promo_code_owners' => $promoCodeOwnerRepository->findAll(),
        ]);
    }

    /**
     * @Route("/{id}", name="promo_code_owner_crud_show", methods={"GET"})
     */
    public function show(PromoCodeOwner $promoCodeOwner): Response
    {
        return $this->render('promo_code_owner_crud/show.html.twig', [
            'promo_code_owner' => $promoCodeOwner,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="promo_code_owner_crud_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, PromoCodeOwner $promoCodeOwner, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(PromoCodeOwnerType::class, $promoCodeOwner);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('promo_code_owner_crud_new', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('promo_code_owner_crud/edit.html.twig', [
            'promo_code_owner' => $promoCodeOwner,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="promo_code_owner_crud_delete", methods={"POST"})
     */
    public function delete(Request $request, PromoCodeOwner $promoCodeOwner, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$promoCodeOwner->getId(), $request->request->get('_token'))) {
            $entityManager->remove($promoCodeOwner);
            $entityManager->flush();
        }

        return $this->redirectToRoute('promo_code_owner_crud_new', [], Response::HTTP_SEE_OTHER);
    }
}
