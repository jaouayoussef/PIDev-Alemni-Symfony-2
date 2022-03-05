<?php

namespace App\Controller;

use App\Entity\Reponse;
use App\Entity\Reclamation;
use App\Form\ReclamationType;
use App\Form\ReponseType;
use App\Repository\ReclamationRepository;
use Doctrine\DBAL\Exception;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/reponse")
 */
class ReponseController extends AbstractController
{
    /**
     * @Route("/", name="all_reclamation", methods={"GET"})
     */
    public function showBackOffice(ReclamationRepository $reclamationRepository,Request $request,PaginatorInterface $paginator): Response
    {
        $reclamations = $reclamationRepository->findAll();
        $pagination = $paginator->paginate(
            $reclamations,
            $request->query->getInt('page', 1),
            2
        );
        return $this->render('reponse/show_all.html.twig', [
            'reclamations' => $pagination,
        ]);
    }

    /**
     * @Route("/noreply", name="no_reply_reclamation", methods={"GET", "POST"})
     * @throws Exception
     */
    public function notifications(ReclamationRepository $reclamationRepository, Request $req,PaginatorInterface $paginator): Response
    {
        $data = $reclamationRepository->findBy(array("status"=>"0"));
        $pagination = $paginator->paginate(
            $data,
            $req->query->getInt('page',1),
            2
        );
        $nbNull = $reclamationRepository->getNonTreatedReports();
        $nb = $reclamationRepository->getTreatedReports();
        return $this->render('reclamation/noreply.html.twig',[
            'noreply'=>$pagination,
            'nbNull'=>$nbNull,
            'nb'=>$nb
        ]);
    }

    /**
     * @Route("/edit/{id}", name="edit_reponse", methods={"GET", "POST"})
     */
    public function editReponse(Request $request, Reponse $reponse, Reclamation $rec, EntityManagerInterface $entityManager): Response
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
     * @Route("/add/{id}", name="add_reponse", methods={"GET", "POST"})
     */
    public function add(Request $request, Reclamation $rec, EntityManagerInterface $entityManager, \Swift_Mailer $mailer): Response
    {
        $reponse = new Reponse();
        $formRec = $this->createForm(ReclamationType::class, $rec);
        $form = $this->createForm(ReponseType::class, $reponse);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $file = $form->get('admin_file')->getData();
            $email = $form->get('email')->getData();
            $name = $formRec->get('name')->getData();
            $reply = $form->get('answer')->getData();
            if ($file) {
                // this is needed to safely include the file name as part of the URL
                $newFilename = uniqid().'.'.$file->guessExtension();
                // Move the file to the directory where pictures are stored
                try {
                    $file->move(
                        $this->getParameter('reclamation_file_dir'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }

                // updates the 'brochureFilename' property to store the PDF file name
                // instead of its contents
                $reponse->setAdminFile($newFilename);
                $message = (new \Swift_Message('Réclamation'))
                    ->setFrom('alemnicontact@gmail.com')
                    ->setTo($email)
                    ->setBody(
                        $this->render('emails/reclamation.html.twig',[
                            'name'=>$name,
                            'reply'=>$reply
                        ]),
                        'text/html'
                    );
                $mailer->send($message);
            }
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
