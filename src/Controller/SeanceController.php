<?php

namespace App\Controller;

use App\Entity\Formation;
use App\Entity\Seance;
use App\Form\SeanceType;
use App\Repository\FormationRepository;
use App\Repository\SeanceRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/seance")
 */
class SeanceController extends AbstractController
{
    /**
     * @Route("/", name="seance_index", methods={"GET"})
     */
    public function index(SeanceRepository $seanceRepository): Response
    {
        return $this->render('seance/index.html.twig', [
            'seances' => $seanceRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new/{id}", name="seance_new", methods={"GET", "POST"})
     */
    public function new(Request $request,Formation $formation, EntityManagerInterface $entityManager,FormationRepository $formationRepository,SeanceRepository $seanceRepository): Response
    {
        $seance = new Seance();
        $form = $this->createForm(SeanceType::class, $seance);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
           #$test= $this->getDoctrine()->getRepository(Formation::class)->find('delete'.$formation.);
            $seance->setFormation($formation);

            $entityManager->persist($seance);
            $entityManager->flush();

            return $this->redirectToRoute('formation_new', [], Response::HTTP_SEE_OTHER);
           }


        return $this->render('seance/new.html.twig', [
            'seance' => $seance,
            'form' => $form->createView(),
            'seances' => $seanceRepository->findAll(),
        ]);
    }

    /**
     * @Route("/{id}", name="seance_show", methods={"GET"})
     */
    public function show(Seance $seance): Response
    {
        return $this->render('seance/show.html.twig', [
            'seance' => $seance,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="seance_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Seance $seance, EntityManagerInterface $entityManager,SeanceRepository $seanceRepository): Response
    {
        $form = $this->createForm(SeanceType::class, $seance);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('formation_new', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('seance/edit.html.twig', [
            'seance' => $seance,
            'form' => $form->createView(),
            'seances' => $seanceRepository->findAll(),
        ]);
    }

    /**
     * @Route("/{id}", name="seance_delete", methods={"POST"})
     */
    public function delete(Request $request, Seance $seance, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$seance->getId(), $request->request->get('_token'))) {
            $entityManager->remove($seance);
            $entityManager->flush();
        }

        return $this->redirectToRoute('formation_new', [], Response::HTTP_SEE_OTHER);
    }
}
