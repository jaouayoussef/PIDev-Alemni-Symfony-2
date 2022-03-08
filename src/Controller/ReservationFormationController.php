<?php

namespace App\Controller;

use App\Entity\ReservationFormation;
use App\Form\ReservationFormationType;
use App\Repository\ReservationFormationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/reservation/formation")
 */
class ReservationFormationController extends AbstractController
{
    /**
     * @Route("/", name="reservation_formation_index", methods={"GET"})
     */
    public function index(ReservationFormationRepository $reservationFormationRepository): Response
    {
        return $this->render('reservation_formation/index.html.twig', [
            'reservation_formations' => $reservationFormationRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="reservation_formation_new", methods={"GET", "POST"})
     */
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $reservationFormation = new ReservationFormation();
        $form = $this->createForm(ReservationFormationType::class, $reservationFormation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($reservationFormation);
            $entityManager->flush();

            return $this->redirectToRoute('reservation_formation_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('reservation_formation/new.html.twig', [
            'reservation_formation' => $reservationFormation,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="reservation_formation_show", methods={"GET"})
     */
    public function show(ReservationFormation $reservationFormation): Response
    {
        return $this->render('reservation_formation/show.html.twig', [
            'reservation_formation' => $reservationFormation,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="reservation_formation_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, ReservationFormation $reservationFormation, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ReservationFormationType::class, $reservationFormation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('reservation_formation_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('reservation_formation/edit.html.twig', [
            'reservation_formation' => $reservationFormation,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="reservation_formation_delete", methods={"POST"})
     */
    public function delete(Request $request, ReservationFormation $reservationFormation, EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();
        if (!$user) {
            return $this->redirectToRoute('app_login');
        } else if ($user->getRoles() == ["ROLE_ADMIN"]) {
            if ($this->isCsrfTokenValid('delete' . $reservationFormation->getId(), $request->request->get('_token'))) {
                $entityManager->remove($reservationFormation);
                $entityManager->flush();
            }

            return $this->redirectToRoute('formationBack_index', [], Response::HTTP_SEE_OTHER);
        } else {
            return $this->redirectToRoute('error');
        }

    }
}
