<?php

namespace App\Controller;

use App\Entity\Useranswer;
use App\Form\UseranswerType;
use App\Repository\UseranswerRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/useranswer")
 */
class UseranswerController extends AbstractController
{

    /**
     * @Route("/", name="useranswer_index", methods={"GET"})
     */

    public function index(UseranswerRepository $useranswerRepository): Response
    {
        return $this->render('useranswer/index.html.twig', [
            'useranswers' => $useranswerRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="useranswer_new", methods={"GET", "POST"})
     */
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $useranswer = new Useranswer();
        $form = $this->createForm(UseranswerType::class, $useranswer);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($useranswer);
            $entityManager->flush();

            return $this->redirectToRoute('useranswer_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('useranswer/new.html.twig', [
            'useranswer' => $useranswer,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="useranswer_show", methods={"GET"})
     */
    public function show(Useranswer $useranswer): Response
    {
        return $this->render('useranswer/show.html.twig', [
            'useranswer' => $useranswer,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="useranswer_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Useranswer $useranswer, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(UseranswerType::class, $useranswer);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('useranswer_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('useranswer/edit.html.twig', [
            'useranswer' => $useranswer,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="useranswer_delete", methods={"POST"})
     */
    public function delete(Request $request, Useranswer $useranswer, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$useranswer->getId(), $request->request->get('_token'))) {
            $entityManager->remove($useranswer);
            $entityManager->flush();
        }

        return $this->redirectToRoute('useranswer_index', [], Response::HTTP_SEE_OTHER);
    }
}
