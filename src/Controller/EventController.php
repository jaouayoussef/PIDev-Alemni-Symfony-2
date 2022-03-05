<?php

namespace App\Controller;

use App\Entity\Event;
use App\Entity\ReservationEvent;
use App\Entity\User;
use App\Form\EventType;
use App\Repository\EventRepository;
use App\Repository\PromotionCodeRepository;
use App\Repository\ReservationEventRepository;
use App\Repository\PromoCodeOwnerRepository;
use App\Repository\PromotionRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use phpDocumentor\Reflection\Types\Void_;
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
 * @Route("/event")
 */
class EventController extends AbstractController
{
    #/**
    # * @Route("/", name="event_index", methods={"GET"})
    # */
    # public function index(EventRepository $eventRepository): Response
    #{
    #    return $this->render('event/index.html.twig', [
    #        'events' => $eventRepository->findAll(),
    #    ]);
    #}
    /**
     * @Route("/show", name="show_event")
     */
    public function index(EventRepository $eventRepository, PromotionRepository $promotionRepository,PromotionCodeRepository $promotionCodeRepository,ReservationEventRepository $reservationEvent): Response
    {
        $eventreservers =  $reservationEvent->findBy(array('UserId' => $this->getUser()->getId()));
        $listid=[];
        $i=0;
        foreach ($eventreservers as $eventreserver){
            $listid[$i]= $eventreserver->getEventId()->getId();
            $i++;
        }
        if (empty($listid)){
            return $this->render('event/show_event/index.html.twig', [
                'Events' => $eventRepository->geteventbydatenow(),
                'Promotions' => $promotionRepository -> getPromotionEVENTbydatenow(),
                'CodePromos'=>  $promotionCodeRepository -> getPromotionCodebydatenow()
            ]);
        }

        return $this->render('event/show_event/index.html.twig', [
            'Events' => $eventRepository->geteventbydatenowandreservation($listid),
            'Promotions' => $promotionRepository -> getPromotionEVENTbydatenow(),
            'CodePromos'=>  $promotionCodeRepository -> getPromotionCodebydatenow()
        ]);
    }
    /**
     * @Route("/showback", name="show_event_back")
     */
    public function showback(ReservationEventRepository $reservationEvent , EventRepository $event): Response
    {
        $eventcharts = $event->findAll();
        $eventname=[];
        $placedispo=[];
        foreach ($eventcharts as $eventchart){
            $eventname[] = $eventchart->getEName();
            $placedispo[] = $eventchart->getENbre() - $eventchart->getEPlaceReserver();

        }

        $reservationEvents = $reservationEvent->AvgBydate();
        $dates=[];
        $PromoCodesCount=[];
        foreach ($reservationEvents as $reservationEven){
            if ($reservationEven['dateMonth'] == "01"){
                $dates[] = "Janvier";
            }
            if ($reservationEven['dateMonth'] == "02"){
                $dates[] = "Février";
            }
            if ($reservationEven['dateMonth'] == "03"){
                $dates[] = "Mars";
            }
            if ($reservationEven['dateMonth'] == "04"){
                $dates[] = "Avril";
            }
            if ($reservationEven['dateMonth'] == "05"){
                $dates[] = "Mai";
            }
            if ($reservationEven['dateMonth'] == "06"){
                $dates[] = "Juin";
            }
            if ($reservationEven['dateMonth'] == "07"){
                $dates[] = "Juillet";
            }
            if ($reservationEven['dateMonth'] == "08"){
                $dates[] = "Août";
            }
            if ($reservationEven['dateMonth'] == "09"){
                $dates[] = "Septembre";
            }
            if ($reservationEven['dateMonth'] == "10"){
                $dates[] = "Octobre";
            }
            if ($reservationEven['dateMonth'] == "11"){
                $dates[] = "Novembre ";
            }
            if ($reservationEven['dateMonth'] == "12"){
                $dates[] = "Décembre";
            }
            $PromoCodesCount[] =  $reservationEven['SUM'];
        }

        return $this->render('event/show_event_back/index.html.twig',[
            'EventReservations' => $reservationEvent->findAll(),
            'PromoDate' => json_encode($dates),
            'PromoCount' => json_encode($PromoCodesCount),
            'nomevent' => json_encode($eventname),
            'nbreplacedispo' => json_encode($placedispo),
        ]);
    }
    /**
     * @Route("/EventReservationAvecIncrement/{id}/{eventid}/{PrixReservaion}/{userid}", name="EventReservationAvecIncrement")
     */
    public function Increment_PCD_NbrePromo($id,$eventid ,$userid,$PrixReservaion,PromoCodeOwnerRepository $PromotionCodeOwnerRepository,EntityManagerInterface $entityManager,UserRepository $userRepository,EventRepository $eventRepository):Response
    {
        $reservationEvent = new ReservationEvent();
        $event = $eventRepository->findOneBy(['id' => $eventid]);
        $event->setEPlaceReserver($event->getEPlaceReserver()+1);
        $user = $userRepository->findOneBy(['id' => $userid]);
        $reservationEvent->setUserId($user);
        $reservationEvent->setEventId($event);
        $reservationEvent->setPrixReservationEvent($PrixReservaion);
        $reservationEvent->setDateReservationEvent(new \DateTime('now'));
        $entityManager->persist($reservationEvent);
        $promocodeOwner = $PromotionCodeOwnerRepository->findOneBy(['id' => $id]);
        $promocodeOwner->setPCDNbrePromo($promocodeOwner->getPCDNbrePromo()+1);
        $entityManager->flush();
        return $this->redirectToRoute('show_event');
    }
    /**
     * @Route("/EventReservationAvecIncrement/{eventid}/{PrixReservaion}/{userid}", name="aJouterReservation")
     */
    public function aJouterReservation($eventid ,$userid,$PrixReservaion,EntityManagerInterface $entityManager,UserRepository $userRepository,EventRepository $eventRepository):Response
    {
        $reservationEvent = new ReservationEvent();
        $event = $eventRepository->findOneBy(['id' => $eventid]);
        $user = $userRepository->findOneBy(['id' => $userid]);
        $event->setEPlaceReserver($event->getEPlaceReserver()+1);
        $reservationEvent->setUserId($user);
        $reservationEvent->setEventId($event);
        $reservationEvent->setPrixReservationEvent($PrixReservaion);
        $reservationEvent->setDateReservationEvent(new \DateTime('now'));
        $entityManager->persist($reservationEvent);
        $entityManager->flush();
        return $this->redirectToRoute('show_event');
    }
    /**
     * @Route("/", name="event_new", methods={"GET", "POST"})
     */
    public function new(Request $request, EntityManagerInterface $entityManager,EventRepository $eventRepository): Response
    {
        $event = new Event();

        $form = $this->createForm(EventType::class, $event)->add('E_PHOTO', FileType::class, [
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
            'label_attr' => [
                'class' => 'form-control',
            ],
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var UploadedFile $brochureFile */
            $image = $form->get('E_PHOTO')->getData();

            if ($image) {
                $newFilename = uniqid().'.'.$image->guessExtension();

                try {
                    $image->move(
                        $this->getParameter('event_picture'),
                        $newFilename
                    );
                } catch (FileException $e) {
                }

                $event->setEPHOTO($newFilename);
            }

            $entityManager->persist($event);
            $entityManager->flush();

            return $this->redirectToRoute('event_new', [], Response::HTTP_SEE_OTHER);
        }
        $CodePromos = $eventRepository->CountBydatedebut();
        $dates=[];
        $EventCount=[];
        foreach ($CodePromos as $CodePromo){
            $dates[] =  $CodePromo['datedebut'];
            $EventCount[] =  $CodePromo['count'];
        }
        $CodePromosFins = $eventRepository->CountBydatefin();
        $datesFin=[];
        $EventCountFin=[];
        foreach ($CodePromosFins as $CodePromofin){
            $datesFin[] =  $CodePromofin['datefin'];
            $EventCountFin[] =  $CodePromofin['countfin'];
        }
        return $this->render('event/new.html.twig', [
            'event' => $event,
            'form' => $form->createView(),
            'events' => $eventRepository->findAll(),
            'EventDateDebut' => json_encode($dates),
            'EventCountDebut' => json_encode($EventCount),
            'EventDateFin' => json_encode($datesFin),
            'EventCountFin' => json_encode($EventCountFin),
        ]);
    }

    /**
     * @Route("/{id}", name="event_show", methods={"GET"})
     */
    public function show(Event $event): Response
    {
        return $this->render('event/show.html.twig', [
            'event' => $event,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="event_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Event $event, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(EventType::class, $event)->add('E_PHOTO', FileType::class, [
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
            'label_attr' => [
                'class' => 'form-control',
            ],
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var UploadedFile $brochureFile */
            $image = $form->get('E_PHOTO')->getData();

            if ($image) {
                $newFilename = uniqid().'.'.$image->guessExtension();

                try {
                    $image->move(
                        $this->getParameter('event_picture'),
                        $newFilename
                    );
                } catch (FileException $e) {
                }

                $event->setEPHOTO($newFilename);
            }
            $entityManager->flush();

            return $this->redirectToRoute('event_new', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('event/edit.html.twig', [
            'event' => $event,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="event_delete", methods={"POST"})
     */
    public function delete(Request $request, Event $event, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$event->getId(), $request->request->get('_token'))) {
            $entityManager->remove($event);
            $entityManager->flush();
        }

        return $this->redirectToRoute('event_new', [], Response::HTTP_SEE_OTHER);
    }
    /**
     * @Route("/eventreservation/{id}", name="eventreservation_delete", methods={"POST"})
     */
    public function deletereservation(Request $request,EventRepository $eventRepository, ReservationEvent $eventReservation, EntityManagerInterface $entityManager): Response
    {

        if ($this->isCsrfTokenValid('delete'.$eventReservation->getId(), $request->request->get('_token'))) {
            $event = $eventRepository->findOneBy(['id' => $eventReservation->getEventId()]);
            $event->setEPlaceReserver($event->getEPlaceReserver()-1);
            $entityManager->remove($eventReservation);
            $entityManager->flush();
        }

        return $this->redirectToRoute('show_event_back', [], Response::HTTP_SEE_OTHER);
    }
}
