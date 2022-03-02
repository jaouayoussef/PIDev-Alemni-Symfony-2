<?php

namespace App\Controller;

use App\Entity\Formation;
use App\Entity\Seance;
use App\Form\FormationType;
use App\Repository\FormationRepository;
use App\Repository\SeanceRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\Pagination\PaginationInterface;
use mysql_xdevapi\CollectionRemove;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\NotBlank;

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
        $form = $this->createForm(FormationType::class, $formation)
            ->add('imageFormation', FileType::class, [


                // unmapped means that this field is not associated to any entity property
                'mapped' => false,

                // make it optional so you don't have to re-upload the PDF file
                // every time you edit the Product details
                'required' => true,

                // unmapped fields can't define their validation using annotations
                // in the associated entity, so you can use the PHP constraint classes
                'constraints' => [
                    new NotBlank(),
                    new File([

                        'mimeTypes' => [
                            'image/*',

                        ],
                        'mimeTypesMessage' => 'merci d"ajouter une image',
                    ])
                ],
            ]);
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
     * @Route("/show/formation", name="formation_show")
     */
    public function show(FormationRepository $formationRepository,SeanceRepository $seanceRepository): Response
    {
        $formations= $formationRepository->findAll();
        $i=0;
        foreach ($formations as $formation){
            $seances=$seanceRepository->findByExampleField($formation->getId());
            $test=1;

            foreach ($seances as $seance){

                if( $seance->getDateSeance() <new \DateTime('now')){
                    $test=0;

                }
            }
            if($test==0){

                unset($formations[$i]) ;

            }
            $i++;
        }


        return $this->render('formation/show.html.twig', [
            'seances' => $seanceRepository->findAll(),
            'formations' => $formations,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="formation_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Formation $formation, EntityManagerInterface $entityManager,SeanceRepository $seanceRepository): Response
    {
        $form = $this->createForm(FormationType::class, $formation)
            ->add('imageFormation', FileType::class, [


                // unmapped means that this field is not associated to any entity property
                'mapped' => false,

                // make it optional so you don't have to re-upload the PDF file
                // every time you edit the Product details
                'required' => false,

                // unmapped fields can't define their validation using annotations
                // in the associated entity, so you can use the PHP constraint classes
                'constraints' => [
                    new File([

                        'mimeTypes' => [
                            'image/*',

                        ],
                        'mimeTypesMessage' => 'merci d"ajouter une image',
                    ])
                ],
            ]);
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
    /**
     * @Route("/test", name="test")
     */
    public function sendEmail( \Swift_Mailer $mailer): Response
    {
        $message = (new \Swift_Message('Hello Email'))
            ->setFrom('alemnicontact@gmail.com')
            ->setTo('charfeddine.ahmed@esprit.tn')
            ->setBody(

                'test'
            );

        $mailer->send($message);


        return $this->redirectToRoute('formation_new', [], Response::HTTP_SEE_OTHER);


    }
}
