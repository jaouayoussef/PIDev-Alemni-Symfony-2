<?php

namespace App\Controller;

use App\Entity\Formation;
use App\Entity\Question;
use App\Entity\Quiz;
use App\Entity\Useranswer;
use App\Entity\Userresult;
use App\Form\QuizType;
use App\Repository\QuizRepository;
use App\Repository\QuestionRepository;
use App\Repository\UseranswerRepository;
use App\Repository\UserresultRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Nucleos\DompdfBundle\Factory\DompdfFactoryInterface;
use Nucleos\DompdfBundle\Wrapper\DompdfWrapperInterface;

/**
 * @Route("/quiz")
 */
class QuizController extends AbstractController
{
    /**
     * @Route("/", name="quiz_index", methods={"GET"})
     */
    public function index(QuizRepository $quizRepository): Response
    {
        $user = $this->getUser();
        if (!$user) {
            return $this->redirectToRoute('app_login');
        } else if ($user->getRoles() != "ROLE_CLIENT") {
            return $this->render('quiz/index.html.twig', [
                'quizzes' => $quizRepository->findBy(array('id_user' => 1)),
            ]);
        } else {
            return $this->redirectToRoute('error');
        }

    }

    /**
     * @Route("/all", name="all", methods={"GET"})
     */
    public function all(QuizRepository $quizRepository): Response
    {
        $user = $this->getUser();
        if (!$user) {
            return $this->redirectToRoute('app_login');
        } else if ($user->getRoles() == "ROLE_TUTOR") {
            return $this->render('quiz/index.html.twig', [
                'quizzes' => $quizRepository->findBy(array('id_user' => $this->getUser()->getId())),
            ]);
        } else {
            return $this->redirectToRoute('error');
        }
    }

    /**
     * @Route("/results/{id}", name="results", methods={"GET"})
     */
    public function results(Quiz $quiz, UserresultRepository $userresultRepository): Response
    {
        $user = $this->getUser();
        if (!$user) {
            return $this->redirectToRoute('app_login');
        } else {
            return $this->render('quiz/result.html.twig', [
                'results' => $userresultRepository->findBy(array('quiz' => $quiz)),
            ]);
        }
    }

    /**
     * @Route("/mesquiz", name="mesquiz", methods={"GET"})
     */

    public function mesquiz(UserresultRepository $userresultRepository)
    {
        //récuperer le dernier quiz passer par l'utilisateur : from user result

        return $this->render('quiz/myquiz.html.twig', [
            'quizresult' => $userresultRepository->findBy(array('id_user' => 1)),
        ]);
    }

    /**
     * @Route("/certif/{id}", name="quiz_certif", methods={"GET"})
     */

    public function certif(Quiz $quiz, QuizRepository $quizRepository)
    {
        $user = $this->getUser();
        if (!$user) {
            return $this->redirectToRoute('app_login');
        } else {
            $userlastname = (string)$this->getUser()->getLastName();
            return $this->render('quiz/certification.html.twig', [
                'quiz' => $quiz,
                'nomFormation' => $quiz->getIdFormation()->getNomFormation(),
                'username' => $user,
                'userlastname' => $userlastname,

            ]);
        }
    }

    /**
     * @Route("/new/{id}", name="quiz_new", methods={"GET", "POST"})
     */
    public function new(Formation $formation, Request $request, EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();
        if (!$user) {
            return $this->redirectToRoute('app_login');
        } else if ($user->getRoles() == "ROLE_TUTOR") {
            $quiz = new Quiz();
            $form = $this->createForm(QuizType::class, $quiz);
            $form->handleRequest($request);
            $user = $this->getUser()->getId();
            if ($form->isSubmitted() && $form->isValid()) {
                $quiz->setIdUser($user);
                $quiz->setIdFormation($formation);
                $entityManager->persist($quiz);
                $entityManager->flush();
                $nbre = 0;
                return $this->redirectToRoute('question_new', ['quiz' => $quiz, 'nbre' => $nbre], Response::HTTP_SEE_OTHER);
            }

            return $this->render('quiz/new.html.twig', [
                'quiz' => $quiz,
                'form' => $form->createView(),
            ]);
        } else {
            return $this->redirectToRoute('error');
        }
    }

    /**
     * @Route("/{id}", name="quiz_show", methods={"GET"})
     */
    public function show(Quiz $quiz): Response
    {
        return $this->render('quiz/show.html.twig', [
            'quiz' => $quiz,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="quiz_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Quiz $quiz, EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();
        if (!$user) {
            return $this->redirectToRoute('app_login');
        } else if ($user->getRoles() == "ROLE_TUTOR") {
            $form = $this->createForm(QuizType::class, $quiz);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $entityManager->flush();

                return $this->redirectToRoute('quiz_index', [], Response::HTTP_SEE_OTHER);
            }

            return $this->render('quiz/edit.html.twig', [
                'quiz' => $quiz,
                'form' => $form->createView(),
            ]);
        } else {
            return $this->redirectToRoute('error');
        }
    }

    /**
     * @Route("/{id}", name="quiz_delete", methods={"POST"})
     */
    public function delete(Request $request, Quiz $quiz, EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();
        if (!$user) {
            return $this->redirectToRoute('app_login');
        } else if ($user->getRoles() == "ROLE_TUTOR") {
            if ($this->isCsrfTokenValid('delete' . $quiz->getId(), $request->request->get('_token'))) {
                $entityManager->remove($quiz);
                $entityManager->flush();
            }

            return $this->redirectToRoute('quiz_index', [], Response::HTTP_SEE_OTHER);
        } else {
            return $this->redirectToRoute('error');
        }

    }

    /**
     * @Route("/{id}/question/{nbr}", name="quizz_play")
     */
    public function play($id, Quiz $quiz, Request $request, $nbr, QuestionRepository $questionRepo, \Swift_Mailer $mailer)
    {
        $user = $this->getUser();
        if (!$user) {
            return $this->redirectToRoute('app_login');
        } else {
            $useranswer = new Useranswer();
            // $question = $questionRepo->find($nbr);
            $questions = $questionRepo->findBy(array('quiz' => $id));

            $question = $questions[$nbr];
            $responses = [
                $question->getReponse1() => 1,
                $question->getReponse2() => 2,
                $question->getReponse3() => 3,
                $question->getReponse4() => 4,


            ];
            $form = $this->createFormBuilder($useranswer)
                ->add('rep_correct', ChoiceType::class, [
                    'label' => $question->getLibelle(),
                    'choices' => $responses,
                    'required' => false,
                    //  'expanded'=> true,
                    //'multiple'=> false,

                ])
                ->getForm();
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {

                $useranswer->setIdQuestion($question->getId());
                $useranswer->setIdQuiz($question->getQuiz()->getId());
                $useranswer->setIdUser(1);

                $em = $this->getDoctrine()->getManager();

                $em->persist($useranswer);
                $em->flush();
                // dd($nbr);

                if ($nbr < 19) {
                    $nbr++;
                    return $this->redirectToRoute('quizz_play', [
                        'form' => $form->createView(),
                        'question' => $question,
                        'nbr' => $nbr,
                        'id' => $id,
                    ]);
                } else {
                    $score = 0;
                    $useranswrep = $this->getDoctrine()->getRepository(Useranswer::class);
                    $useranswers = $useranswrep->findBy(array('id_quiz' => $id));
                    foreach ($useranswers as $us) {
                        $repcorr = $questionRepo->find($us->getIdQuestion())->getRepcorrect();
                        if ($us->getRepCorrect() == $repcorr) {
                            $score++;
                        }
                    }
                    $userresult = new Userresult();
                    $userresult->setIdUser($this->getUser());
                    $userresult->setQuiz($quiz);
                    $userresult->setResult($score);
                    $em = $this->getDoctrine()->getManager();

                    $em->persist($userresult);
                    $em->flush();
                    if ($score < 18) {
                        return $this->render('quiz/error.html.twig', [
                            'form' => $form->createView(),
                            'question' => $question,
                            'nbr' => $nbr,
                            'id' => $id,
                            'score' => $score,
                        ]);
                    } else {
                        $message = (new \Swift_Message('Félicitation'))
                            ->setFrom('nassim.allouche@gmail.com')
                            ->setTo($this->getUser()->getUsername())
                            ->setBody(
                                $this->renderView(
                                // templates/emails/registration.html.twig
                                    'quiz/mail.html.twig', [

                                        'question' => $question,


                                        'score' => $score,
                                    ]

                                ),
                                'text/html'
                            );
                        $mailer->send($message);

                        return $this->render('quiz/success.html.twig', [
                            'form' => $form->createView(),
                            'question' => $question,
                            'nbr' => $nbr,
                            'id' => $id,
                            'score' => $score,
                        ]);
                    }
                }
            }

            return $this->render('quiz/play.html.twig', [
                'form' => $form->createView(),
                'question' => $question,
                'nbr' => $nbr,
                'id' => $id,
            ]);
        }

    }


}
