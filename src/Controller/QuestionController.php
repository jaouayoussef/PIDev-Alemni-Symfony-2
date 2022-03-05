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
        return $this->render('question/index.html.twig', [
            'questions' => $questionRepository->findAll(),
        ]);
    }
    /**
     * @Route("/myquestion/{id}", name="myquestion", methods={"GET"})
     */
    public function myquestion(Quiz $question): Response
    {
        return $this->render('question/index.html.twig', [
            'questions' => $this->getDoctrine()->getRepository(Question::class)->findByExampleField($question->getId()),
        ]);
    }

    /**
     * @Route("/new", name="question_new", methods={"GET", "POST"})
     */
  /*  public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $array = array();
        $question = new Question();
        $indice =count($array);
        $nbr = count($this->getDoctrine()->getRepository(Quiz::class)->findBy(array('id_user'=>$this->getUser()->getId()))) ;
        $quizs =$this->getDoctrine()->getRepository(Quiz::class)->findBy(array('id_user'=>$this->getUser()->getId()));
        $quiz=$quizs[$nbr -1];
        $form = $this->createForm(QuestionType::class, $question);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {


            if ( $indice < 4){
                $array[$indice++] = $question;
                return $this->redirectToRoute('question_new', [
                    'quiz' =>  $quiz,
                    'question' => $question,
                    'form' => $form->createView(),
                    'nbre'=> $indice,
                ],Response::HTTP_SEE_OTHER);
            } else{ dd($array);return $this->forward('App\Controller\QuestionController::myquestion', ['question'=>$quiz]);}
        }
        return $this->render('question/new.html.twig', [
            'quiz' =>  $quiz,
            'question' => $question,
            'form' => $form->createView(),
            'nbre'=>$indice ,
        ]);

    }*/
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {

        $question = new Question();

        $nbr = count($this->getDoctrine()->getRepository(Quiz::class)->findBy(array('id_user'=>$this->getUser()->getId()))) ;
        $quizs =$this->getDoctrine()->getRepository(Quiz::class)->findBy(array('id_user'=>$this->getUser()->getId()));
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
        $form = $this->createForm(QuestionType::class, $question);
        $form->handleRequest($request);

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
        if ($this->isCsrfTokenValid('delete'.$question->getId(), $request->request->get('_token'))) {
            $entityManager->remove($question);
            $entityManager->flush();
        }

        return $this->redirectToRoute('question_index', [], Response::HTTP_SEE_OTHER);
    }
}
