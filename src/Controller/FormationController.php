<?php

namespace App\Controller;

use App\Entity\Formation;
use App\Form\FormationType;
use App\Repository\FormationRepository;
use App\Repository\SeanceRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/formation")
 */
class FormationController extends AbstractController
{
    /**
     * @Route("/", name="formation_index", methods={"GET"})
     */
    public function index(FormationRepository $formationRepository): Response
    {
        return $this->render('formation/index.html.twig', [
            'formations' => $formationRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="formation_new", methods={"GET", "POST"})
     */
    public function new(Request $request, EntityManagerInterface $entityManager,FormationRepository $formationRepository): Response
    {
        $formation = new Formation();
        $form = $this->createForm(FormationType::class, $formation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var UploadedFile $brochureFile */
            $image = $form->get('imageFormation')->getData();

            if ($image) {
                $newFilename = uniqid().'.'.$image->guessExtension();

                try {
                    $image->move(
                        $this->getParameter('image_formation'),
                        $newFilename
                    );
                } catch (FileException $e) {
                }

                $formation->setImageFormation($newFilename);
            }
            $entityManager->persist($formation);
            $entityManager->flush();

            return $this->redirectToRoute('formation_new', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('formation/new.html.twig', [
            'formation' => $formation,
            'form' => $form->createView(),
            'formations' => $formationRepository->findAll(),
        ]);
    }

    /**
     * @Route("/show", name="formation_show")
     */
    public function show(FormationRepository $formationRepository,SeanceRepository $seanceRepository): Response
    {
        return $this->render('formation/show.html.twig', [
            'seances' => $seanceRepository->findAll(),
            'formations' => $formationRepository->findAll(),
        ]);
    }

    /**
     * @Route("/{id}/edit", name="formation_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Formation $formation, EntityManagerInterface $entityManager,SeanceRepository $seanceRepository): Response
    {
        $form = $this->createForm(FormationType::class, $formation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var UploadedFile $brochureFile */
            $image = $form->get('imageFormation')->getData();

            if ($image) {
                $newFilename = uniqid().'.'.$image->guessExtension();

                try {
                    $image->move(
                        $this->getParameter('image_formation'),
                        $newFilename
                    );
                } catch (FileException $e) {
                }

                $formation->setImageFormation($newFilename);
            }
            $entityManager->flush();

            return $this->redirectToRoute('formation_new', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('formation/edit.html.twig', [
            'formation' => $formation,
            'form' => $form->createView(),
            'seances' => $seanceRepository->findByExampleField($formation->getId()),
        ]);
    }

    /**
     * @Route("/{id}", name="formation_delete", methods={"POST"})
     */
    public function delete(Request $request, Formation $formation, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$formation->getId(), $request->request->get('_token'))) {
            $entityManager->remove($formation);
            $entityManager->flush();
        }

        return $this->redirectToRoute('formation_new', [], Response::HTTP_SEE_OTHER);
    }
}
