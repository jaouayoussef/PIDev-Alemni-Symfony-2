<?php

namespace App\Controller;

use App\Entity\Reponse;
use App\Entity\Reclamation;
use App\Form\ReclamationType;
use App\Form\ReponseType;
use App\Repository\ReclamationRepository;
use App\Repository\ReponseRepository;
use Doctrine\ORM\EntityManagerInterface;
use Pagerfanta\Doctrine\ORM\QueryAdapter;
use Pagerfanta\Pagerfanta;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/reponse")
 */
class ReponseController extends AbstractController
{
    /**
     * @Route("//{page<\d+>}", name="all_reclamation", methods={"GET"})
     */
    public function showBackOffice(ReclamationRepository $reclamationRepository, ReponseRepository $reponseRepository, int $page = 1): Response
    {

        return $this->render('reponse/show_all.html.twig', [
            'reclamations' => $reclamationRepository->getAllAnswers(),
        ]);
    }

    /**
     * @Route("/add/{id}", name="add_reponse", methods={"GET", "POST"})
     */
    public function add(Request $request, Reclamation $rec, EntityManagerInterface $entityManager): Response
    {
        $reponse = new Reponse();
        $formRec = $this->createForm(ReclamationType::class, $rec);
        $form = $this->createForm(ReponseType::class, $reponse);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $reponse->setReclamation($rec);
            $reponse->setReplyDate(new \DateTime());
            $rec->setStatus('answered');
            $entityManager->persist($rec);
            $entityManager->persist($reponse);
            $entityManager->flush();

            return $this->redirectToRoute('all_reclamation', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('reponse/new.html.twig', [
            'reponse' => $reponse,
            'form' => $form->createView(),
            'form_rec' => $formRec->createView(),
        ]);
    }

    // /**
    //  * @Route("/{id}", name="reponse_show", methods={"GET"})
    //  */
    // public function show(Reponse $reponse): Response
    // {
    //     return $this->render('reponse/show.html.twig', [
    //         'reponse' => $reponse,
    //     ]);
    // }

    /**
     * @Route("/edit/{id}", name="edit_reponse", methods={"GET", "POST"})
     */
    public function edit(Request $request, Reponse $reponse, Reclamation $rec, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ReponseType::class, $reponse);
        $formRec = $this->createForm(ReclamationType::class, $rec);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $reponse->setReplyDate(new \DateTime());
            $entityManager->flush();

            return $this->redirectToRoute('all_reclamation', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('reponse/new.html.twig', [
            'reponse' => $reponse,
            'form' => $form->createView(),
            'form_rec' => $formRec->createView(),
        ]);
    }

    /**
     * @Route("/delete/{id}", name="delete_reponse", methods={"POST"})
     */
    public function delete(Request $request, Reponse $reponse, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$reponse->getId(), $request->request->get('_token'))) {
            $entityManager->remove($reponse);
            $entityManager->flush();
        }

        return $this->redirectToRoute('all_reclamation', [], Response::HTTP_SEE_OTHER);
    }
}
