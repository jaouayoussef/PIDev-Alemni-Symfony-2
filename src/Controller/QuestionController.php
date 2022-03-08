<?php

namespace App\Controller;

use App\Entity\Question;
use App\Entity\Quiz;
use App\Form\QuestionType;
use App\Repository\QuestionRepository;
use App\Repository\QuizRepository;
use Doctrine\ORM\EntityManagerInterface;
use phpDocumentor\Reflection\Types\Integer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/question")
 */
class QuestionController extends AbstractController
{
    /**
     * @Route("/", name="question_index", methods={"GET"})
     */
    public function index(QuestionRepository $questionRepository): Response
    {
<<<<<<< Updated upstream
        return $this->render('question/index.html.twig', [
            'questions' => $questionRepository->findAll(),
        ]);
=======
        $user = $this->getUser();
        if (!$user) {
            return $this->redirectToRoute('app_login');
        } else if ($user->getRoles() == ["ROLE_TUTOR"]) {
            return $this->render('question/index.html.twig', [
                'questions' => $questionRepository->findAll(),
            ]);
        } else {
            return $this->redirectToRoute('error');
        }

>>>>>>> Stashed changes
    }
    /**
     * @Route("/myquestion/{id}", name="myquestion", methods={"GET"})
     */
    public function myquestion(Quiz $question): Response
    {
<<<<<<< Updated upstream
        return $this->render('question/index.html.twig', [
            'questions' => $this->getDoctrine()->getRepository(Question::class)->findByExampleField($question->getId()),
        ]);
=======
        $user = $this->getUser();
        if (!$user) {
            return $this->redirectToRoute('app_login');
        } else if ($user->getRoles() == ["ROLE_TUTOR"]) {
            return $this->render('question/index.html.twig', [
                'questions' => $this->getDoctrine()->getRepository(Question::class)->findByExampleField($question->getId()),
            ]);
        } else {
            return $this->redirectToRoute('error');
        }

>>>>>>> Stashed changes
    }

    /**
     * @Route("/new", name="question_new", methods={"GET", "POST"})
     */
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
<<<<<<< Updated upstream
        $question = new Question();

        $nbr = count($this->getDoctrine()->getRepository(Quiz::class)->findAll()) ;
        $quizs =$this->getDoctrine()->getRepository(Quiz::class)->findAll();
        $quiz=$quizs[$nbr -1];
        //$quiz =$this->getDoctrine()->getRepository(Quiz::class)->find(50);
        $form = $this->createForm(QuestionType::class, $question);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $question->setQuiz($quiz);
            $entityManager->persist($question);
            $entityManager->flush();



         $nbre = count($this->getDoctrine()->getRepository(Question::class)->findByExampleField($question->getQuiz()->getId()));
          if ( $nbre < 20){

            return $this->redirectToRoute('question_new', [
                'quiz' =>  $quiz,
=======
        $user = $this->getUser();
        if (!$user) {
            return $this->redirectToRoute('app_login');
        } else if ($user->getRoles() == ["ROLE_TUTOR"]) {
            $question = new Question();

            $nbr = count($this->getDoctrine()->getRepository(Quiz::class)->findBy(array('id_user' => $this->getUser()->getId())));
            $quizs = $this->getDoctrine()->getRepository(Quiz::class)->findBy(array('id_user' => $this->getUser()->getId()));
            $quiz = $quizs[$nbr - 1];
            //$quiz =$this->getDoctrine()->getRepository(Quiz::class)->find(50);
            $form = $this->createForm(QuestionType::class, $question);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $question->setQuiz($quiz);


                $entityManager->persist($question);
                $entityManager->flush();


                $nbre = count($this->getDoctrine()->getRepository(Question::class)->findByExampleField($question->getQuiz()->getId()));
                if ($nbre < 20) {

                    return $this->redirectToRoute('question_new', [
                        'quiz' => $quiz,
                        'question' => $question,
                        'form' => $form->createView(),
                        'nbre' => $nbre,
                    ], Response::HTTP_SEE_OTHER);
                } else {
                    return $this->forward('App\Controller\QuestionController::myquestion', ['question' => $quiz]);
                }
            }
            $nbre = count($this->getDoctrine()->getRepository(Question::class)->findByExampleField($quiz->getId()));

            return $this->render('question/new.html.twig', [
                'quiz' => $quiz,
>>>>>>> Stashed changes
                'question' => $question,
                'form' => $form->createView(),
                'nbre'=> $nbre,
            ],Response::HTTP_SEE_OTHER);
        } else{ return $this->forward('App\Controller\QuestionController::myquestion', ['question'=>$quiz]);}
    }
        $nbre = count($this->getDoctrine()->getRepository(Question::class)->findByExampleField($quiz->getId()));

        return $this->render('question/new.html.twig', [
            'quiz' =>  $quiz,
            'question' => $question,
            'form' => $form->createView(),
            'nbre'=>$nbre,
        ]);
    }

    /**
     * @Route("/{id}", name="question_show", methods={"GET"})
     */
    public function show(Question $question): Response
    {
        return $this->render('question/show.html.twig', [
            'question' => $question,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="question_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Question $question, EntityManagerInterface $entityManager): Response
    {
<<<<<<< Updated upstream
        $form = $this->createForm(QuestionType::class, $question);
        $form->handleRequest($request);
=======
        $user = $this->getUser();
        if (!$user) {
            return $this->redirectToRoute('app_login');
        } else if ($user->getRoles() == ["ROLE_TUTOR"]) {
            $form = $this->createForm(QuestionType::class, $question);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $entityManager->flush();
>>>>>>> Stashed changes

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return  $this->forward('App\Controller\QuestionController::myquestion', ['question'=>$question->getQuiz()]);;
        }

        return $this->render('question/edit.html.twig', [
            'question' => $question,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="question_delete", methods={"POST"})
     */
    public function delete(Request $request, Question $question, EntityManagerInterface $entityManager): Response
    {
<<<<<<< Updated upstream
        if ($this->isCsrfTokenValid('delete'.$question->getId(), $request->request->get('_token'))) {
            $entityManager->remove($question);
            $entityManager->flush();
=======
        $user = $this->getUser();
        if (!$user) {
            return $this->redirectToRoute('app_login');
        } else if ($user->getRoles() == ["ROLE_TUTOR"]) {
            if ($this->isCsrfTokenValid('delete' . $question->getId(), $request->request->get('_token'))) {
                $entityManager->remove($question);
                $entityManager->flush();
            }

            return $this->redirectToRoute('question_index', [], Response::HTTP_SEE_OTHER);
        } else {
            return $this->redirectToRoute('error');
>>>>>>> Stashed changes
        }

        return $this->redirectToRoute('question_index', [], Response::HTTP_SEE_OTHER);
    }
}
