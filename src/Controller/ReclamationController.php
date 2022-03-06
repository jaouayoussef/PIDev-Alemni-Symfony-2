<?php

namespace App\Controller;

use App\Entity\Reclamation;
use App\Form\ReclamationType;
use App\Repository\ReclamationRepository;
use Doctrine\DBAL\Exception;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints\File;

/**
 * @Route("/reclamation")
 */
class ReclamationController extends AbstractController
{
    /**
     * @Route("/", name="user_reclamation", methods={"GET"})
     * @throws Exception
     */
    public function index(ReclamationRepository $reclamationRepository, Request $req, PaginatorInterface $paginator): Response
    {
        $data = $reclamationRepository->getAllAnswers();
        $pagination = $paginator->paginate(
            $data,
            $req->query->getInt('page', 1),
            2
        );
        return $this->render('reclamation/show_front.html.twig', [
            'reclamations' => $pagination,
        ]);

    }

    /**
     * @Route("/add", name="add_reclamation", methods={"GET", "POST"})
     */
    public function addReclamation(Request $request, EntityManagerInterface $entityManager): Response
    {
        $reclamation = new Reclamation();
        $form = $this->createForm(ReclamationType::class, $reclamation)->add('UserFile', FileType::class, [
            'required' => false,
            'mapped' => false,
            'constraints' => [
                new File()
            ]
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $file = $form->get('UserFile')->getData();
            if ($file) {
                // this is needed to safely include the file name as part of the URL
                $newFilename = uniqid() . '.' . $file->guessExtension();
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
                $reclamation->setUserFile($newFilename);
            }
            $reclamation->setSendingDate(new \DateTime());
            $entityManager->persist($reclamation);
            $entityManager->flush();

            return $this->redirectToRoute('user_reclamation', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('reclamation/add_rec.html.twig', [
            'reclamation' => $reclamation,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="delete_reclamation", methods={"POST"})
     */
    public function delete(Request $request, Reclamation $reclamation, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $reclamation->getId(), $request->request->get('_token'))) {
            $entityManager->remove($reclamation);
            $entityManager->flush();
        }

        return $this->redirectToRoute('user_reclamation', [], Response::HTTP_SEE_OTHER);
    }



    public function getStatDate($data)
    {
        $res = array(0,0,0,0,0,0,0,0,0,0,0,0) ;
        foreach ($data as $r)
        {
            $index = $r->getSendingDate()->format('m') ;
            if ((int)$index >= 10)
                $index = $r->getSendingDate()->format('m') - 1 ;
            else
                $index = $r->getSendingDate()->format('m')[1] - 1 ;
            $res[$index]++ ;
        }

        return $res ;
    }


    /**
     * @Route("/stat", name="stat_reclamation", methods={"GET"})
     */
    public function stat(ReclamationRepository $reclamationRepository)
    {
        $data=$reclamationRepository->findAll();
        $res = $this->getStatDate($data) ;
        $repondu = 0 ;
        $nonRepondu = 0 ;
        $type1 = 0 ;
        $type2 = 0 ;
        $type3 = 0 ;
        $type4 = 0 ;
        foreach ($data as $t)
        {
            $type=$t->getType();
            if ($type=="Account"){
                $type1++;
            }
            elseif ($type=="Course"){
                $type2++;
            }
            elseif ($type=="Event"){
                $type3++;
            }
            elseif ($type=="Others"){
                $type4++;
            }

            if ($t->getReponse() == null)
            {
                $nonRepondu++ ;
            } elseif ($t->getReponse() != null)
            {
                $repondu++ ;
            }

        }
        $reclamation= $reclamationRepository->countbydate();
        $dates = [];
        $reclamationcount = [];

        foreach ($reclamation as $reclame){
            $dates[]= $reclame['date_reclamtion'];
            $reclamationcount[]=$reclame['count'];

        }
        $choice=['Account','Course','Event','Others'];

        return $this->render('reponse/stat.html.twig' ,
            [
                'type1' => $type1,
                'type2' => $type2,
                'type3' => $type3,
                'type4' => $type4,
                'choice'=>json_encode($choice),
                'dates'=>json_encode($dates),
                'reclamationcount'=>json_encode($reclamationcount) ,
                'etat' => [$repondu,$nonRepondu] ,
                'res' => $res
            ]
        );
    }
}
