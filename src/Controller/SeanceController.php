<?php

namespace App\Controller;

use App\Entity\Formation;
use App\Entity\Quiz;
use App\Entity\Seance;
use App\Form\SeanceType;
use App\Repository\FormationRepository;
use App\Repository\QuizRepository;
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
    /*public function index(SeanceRepository $seanceRepository): Response
    {
        return $this->render('seance/index.html.twig', [
            'seances' => $seanceRepository->findAll(),
        ]);
    }*/
    /**
     * @Route("/{id}/editSeance", name="seance1_edit", methods={"GET", "POST"})
     */
    public function editSeance(Request $request, Seance $seance, EntityManagerInterface $entityManager, SeanceRepository $seanceRepository): Response
    {
        $user = $this->getUser();
        if (!$user) {
            return $this->redirectToRoute('app_login');
        } else if ($user->getRoles() == ["ROLE_TUTOR"]) {
            $form = $this->createForm(SeanceType::class, $seance);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $entityManager->flush();

                return $this->redirectToRoute('formation_new', [], Response::HTTP_SEE_OTHER);
            }

            return $this->render('seance/edit.html.twig', [
                'seance' => $seance,
                'form' => $form->createView(),
                'seances' => $seanceRepository->findByExampleField($seance->getFormation()->getId()),
            ]);
        } else {
            return $this->redirectToRoute('error');
        }

    }

    /**
     * @Route("/new/{id}", name="seance_new", methods={"GET", "POST"})
     */
    public function new(Request $request, Formation $formation, EntityManagerInterface $entityManager, FormationRepository $formationRepository, SeanceRepository $seanceRepository): Response
    {
        $user = $this->getUser();
        if (!$user) {
            return $this->redirectToRoute('app_login');
        } else if ($user->getRoles() == ["ROLE_TUTOR"]) {
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
                'seances' => $seanceRepository->findByExampleField($formation->getId()),
            ]);
        } else {
            return $this->redirectToRoute('error');
        }
    }

    /**
     * @Route("/{id}/edit", name="seance_edit", methods={"GET", "POST"}, requirements={"id":"\d+"})
     */
    public function edit(Request $request, Formation $formation, Seance $seance, EntityManagerInterface $entityManager, SeanceRepository $seanceRepository): Response
    {
        $user = $this->getUser();
        if (!$user) {
            return $this->redirectToRoute('app_login');
        } else if ($user->getRoles() == ["ROLE_TUTOR"]) {
            $form = $this->createForm(SeanceType::class, $seance);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $entityManager->flush();

                return $this->redirectToRoute('formation_new', [], Response::HTTP_SEE_OTHER);
            }

            return $this->render('seance/edit.html.twig', [
                'seance' => $seance,
                'form' => $form->createView(),
                'seances' => $seanceRepository->findByExampleField($formation->getId()),
            ]);
        } else {
            return $this->redirectToRoute('error');
        }
    }

    /**
     * @Route("/{id}/test", name="seance_show", methods={"GET"})
     */
    public function show(Seance $seance): Response
    {
        return $this->render('seance/show.html.twig', [
            'seance' => $seance,
        ]);
    }

    /**
     * @Route("/{id}", name="seance_delete", methods={"POST"})
     */
    public function delete(Request $request, Seance $seance, EntityManagerInterface $entityManager, QuizRepository $quizRepository): Response
    {
        $user = $this->getUser();
        if (!$user) {
            return $this->redirectToRoute('app_login');
        } else if ($user->getRoles() == ["ROLE_TUTOR"]) {
            if ($this->isCsrfTokenValid('delete' . $seance->getId(), $request->request->get('_token'))) {
                $entityManager->remove($seance);
                $entityManager->flush();
            }
            if ($seance->getFormation()->getQuiz() != null) {
                $quiz = $quizRepository->find($seance->getFormation()->getQuiz()->getId());
                if ($seance->getFormation()->getSeances()->count() == 0) {
                    if ($seance->getFormation()->getQuiz() != null) {
                        $entityManager->remove($quiz);
                        $entityManager->flush();
                    }
                }
            }
            return $this->redirectToRoute('formation_new', [], Response::HTTP_SEE_OTHER);
        } else {
            return $this->redirectToRoute('error');
        }

    }
}
