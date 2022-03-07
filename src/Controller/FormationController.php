<?php

namespace App\Controller;

use App\Entity\Formation;
use App\Entity\ReservationFormation;
use App\Entity\Seance;
use App\Form\FormationType;
use App\Repository\DomaineRepository;
use App\Repository\FormationRepository;
use App\Repository\PromoCodeOwnerRepository;
use App\Repository\PromotionCodeRepository;
use App\Repository\PromotionRepository;
use App\Repository\ReservationFormationRepository;
use App\Repository\SeanceRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
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
    public function index(DomaineRepository $domaineRepository,FormationRepository $formationRepository,UserRepository $userRepository): Response
    {
        return $this->render('formation/index.html.twig', [
            'formateurs' => $userRepository->getUserByRole('ROLE_TUTOR'),
            'formations' => $formationRepository->findAll(),
            'domaines' => $domaineRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="formation_new", methods={"GET", "POST"})
     */
    public function new(Request $request, EntityManagerInterface $entityManager,FormationRepository $formationRepository,UserRepository $userRepository): Response
    {
        $user = $this->getUser();
        if(!$user){
            return $this->redirectToRoute('app_login');
        }
            if($user->getRoles() == ["ROLE_TUTOR"]){
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
                $formation->setPlacesReserve(0);
                $formation->setFormateur($userRepository->find($user->getId()));
                $entityManager->persist($formation);
                $entityManager->flush();

                return $this->redirectToRoute('formation_new', [], Response::HTTP_SEE_OTHER);
            }

            return $this->render('formation/new.html.twig', [
                'formation' => $formation,
                'form' => $form->createView(),
                'formations' => $formationRepository->getFormationByUser($user->getId()),
            ]);
        }else{
            return $this->redirectToRoute('home');
        }

    }

    /**
     * @Route("/show", name="formation_show")
     */
    public function show(FormationRepository $formationRepository,ReservationFormationRepository $reservationFormationRepository,SeanceRepository $seanceRepository,PromotionRepository $promotionRepository,PromotionCodeRepository $promotionCodeRepository): Response
    {
        $user = $this->getUser();
        $formations= $formationRepository->findAll();
        $reserves=$reservationFormationRepository->findOneBySomeField($user->getId());
        $i=0;
        $j=0;
        foreach ($formations as $formation){
            $seances=$seanceRepository->findByExampleField($formation->getId());
            $test=1;
            if( empty($seances)){
                $test=0;

            }
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
        foreach ($formations as $formation){

            $test=1;
            if($formation->getPlacesReserve()==$formation->getNbPlaces()){
                $test=0;
            }

            foreach ($reserves as $reserve){

                if( $reserve->getFormation()==$formation){
                    $test=0;

                }
            }
            if($test==0){

                unset($formations[$j]) ;

            }
            $j++;
        }

        return $this->render('formation/show.html.twig', [
            'Promotions' => $promotionRepository -> getPromotionEVENTbydatenowDomaine(),
            'CodePromos'=>  $promotionCodeRepository -> getPromotionCodebydatenow(),
            'seances' => $seanceRepository->findAll(),
            'formations' => $formations,

        ]);
    }

    /**
     * @Route("/{id}/edit", name="formation_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Formation $formation, EntityManagerInterface $entityManager,SeanceRepository $seanceRepository,UserRepository $userRepository): Response
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
            $formation->setFormateur($userRepository->find(1));
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

    //TODO:FINISH FUNCTION
    /* public function sendEmail( \Swift_Mailer $mailer,UserRepository $userRepository,Formation $formation, EntityManagerInterface $entityManager): Response
   {
       $reservationFormation = new ReservationFormation();
       $reservationFormation->setUser($userRepository->find(1));
       $reservationFormation->setDate(new \DateTime('now'));
       $reservationFormation->setPrix();
       $reservationFormation->setFormation($formation);
       $entityManager->persist($reservationFormation);
       $entityManager->flush();
       $message = (new \Swift_Message('Hello Email'))
           ->setFrom('alemnicontact@gmail.com')
           ->setTo('charfeddine.ahmed@esprit.tn')
           ->setBody(

               'lien'+$formation->getLien()
           );

       $mailer->send($message);


       return $this->redirectToRoute('formation_new', [], Response::HTTP_SEE_OTHER);


   }*/
    /**
     * @Route("/EventReservationAvecIncrement/{id}/{eventid}/{PrixReservaion}/{userid}", name="EventReservationAvecIncrement")
     */
    public function Increment_PCD_NbrePromo($id,$eventid ,$userid,$PrixReservaion,\Swift_Mailer $mailer,PromoCodeOwnerRepository $PromotionCodeOwnerRepository,EntityManagerInterface $entityManager,UserRepository $userRepository,FormationRepository $FormationRepository):Response
    {
        $reservationFormation = new ReservationFormation();
        $formation = $FormationRepository->findOneBy(['id' => $eventid]);
        $formation->setPlacesReserve($formation->getPlacesReserve()+1);
        $user = $userRepository->findOneBy(['id' => $userid]);
        $reservationFormation->setUser($user);
        $reservationFormation->setFormation($formation);
        $reservationFormation->setPrix($PrixReservaion);
        $reservationFormation->setDate(new \DateTime('now'));
        $entityManager->persist($reservationFormation);
        $promocodeOwner = $PromotionCodeOwnerRepository->findOneBy(['id' => $id]);
        $promocodeOwner->setPCDNbrePromo($promocodeOwner->getPCDNbrePromo()+1);
        $message = (new \Swift_Message('ALEMNI, Paiement effectué!'))
            ->setFrom('alemnicontact@gmail.com')
            ->setTo($this->getUser()->getEmail())
            ->setBody(
                $this->renderView(
                // templates/emails/registration.html.twig
                    'formation/mail.html.twig',
                    ['formation' => $formation->getNomFormation(),
                        'firstname'=> $this->getUser()->getFirstName() ,
                        'lastname' =>$this->getUser()->getLastName() ,
                        'montant'=>$PrixReservaion,
                        'lien'=> $formation->getLien()

                    ]
                ),
                'text/html'
            )
        ;

        $mailer->send($message);
        $entityManager->flush();
        return $this->redirectToRoute('formation_show');
    }
    /**
     * @Route("/EventReservationAvecIncrement/{eventid}/{PrixReservaion}/{userid}", name="aJouterReservation")
     */
    public function aJouterReservation($eventid ,$userid,$PrixReservaion,EntityManagerInterface $entityManager,\Swift_Mailer $mailer,UserRepository $userRepository,FormationRepository $FormationRepository):Response
    {
        $reservationFormation = new ReservationFormation();
        $formation = $FormationRepository->findOneBy(['id' => $eventid]);
        $formation->setPlacesReserve($formation->getPlacesReserve()+1);
        $user = $userRepository->findOneBy(['id' => $userid]);
        $reservationFormation->setUser($user);
        $reservationFormation->setFormation($formation);
        $reservationFormation->setPrix($PrixReservaion);
        $reservationFormation->setDate(new \DateTime('now'));
        $entityManager->persist($reservationFormation);
        $message = (new \Swift_Message('ALEMNI, Paiement effectué!'))
            ->setFrom('alemnicontact@gmail.com')
            ->setTo($this->getUser()->getEmail())
            ->setBody(
                $this->renderView(
                // templates/emails/registration.html.twig
                    'formation/mail.html.twig',
                    ['formation' => $formation->getNomFormation(),
                        'firstname'=> $this->getUser()->getFirstName() ,
                        'lastname' =>$this->getUser()->getLastName() ,
                        'montant'=>$PrixReservaion,
                        'lien'=> $formation->getLien()

                    ]
                ),
                'text/html'
            )
        ;

        $mailer->send($message);
        $entityManager->flush();
        return $this->redirectToRoute('formation_show');

    }
}
