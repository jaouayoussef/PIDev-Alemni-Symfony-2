<?php /** @noinspection ALL */

namespace App\Controller;

use App\Entity\ReservationEvent;
use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Repository\EventRepository;
use App\Repository\PromoCodeOwnerRepository;
use App\Repository\ReservationEventRepository;
use App\Repository\UserRepository;
use App\Repository\UserresultRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Omines\DataTablesBundle\Column\TextColumn;
use Omines\DataTablesBundle\DataTableFactory;
use Pagerfanta\Doctrine\ORM\QueryAdapter;
use Pagerfanta\Pagerfanta;
use App\Repository\ReservationFormationRepository;
use App\Repository\SeanceRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserController extends AbstractController
{
    /**
     * @Route("/error", name="error")
     */
    public function notFound()
    {
        return $this->render('security/404.html.twig');
    }

    /**
     * @Route("/user/profile", name="user_profile")
     */
    public function getUserProfile(ReservationEventRepository $reservationevent,SeanceRepository $seanceRepository,ReservationFormationRepository $reservationFormationRepository, UserresultRepository $userresultRepository): Response
    {
        $user = $this->getUser();
        if (!$user) {
            return $this->redirectToRoute('app_login');
        }
        if ($user->getRoles() == ["ROLE_ADMIN"]) {
            return $this->redirectToRoute('admin_profile');
        } else {

            return $this->render('user/userProfile.html.twig', [
                'user' => $user,
                'events' => $reservationevent->getbyuser($user->getId()),
                'formations' => $reservationFormationRepository->findOneBySomeField($user->getId()),
                'seances' => $seanceRepository->findAll(),
                'userresults' => $userresultRepository->findBy(array('id_user'=>$this->getUser()->getId()))
            ]);
        }
    }

    /**
     * @Route("/admin/profile", name="admin_profile")
     */
    public function getAdminProfile(): Response
    {
        $user = $this->getUser();
        if (!$user) {
            return $this->redirectToRoute('app_login');
        }
        if ($user->getRoles() == ["ROLE_ADMIN"]) {
            return $this->render('admin/adminProfile.html.twig', [
                'user' => $user,
            ]);
        } else {
            return $this->redirectToRoute('user_profile');
        }
    }

    /**
     * @Route("/user/edit", name="edit_user")
     */
    public function editUserAccount(Request $req, UserPasswordEncoderInterface $encoder): Response
    {

        $user = $this->getUser();
        if (!$user) {
            return $this->redirectToRoute('app_login');
        } else {
            $em = $this->getDoctrine()->getManager();
            $form = $this->createForm(RegistrationFormType::class, $user)
                ->add('old_password', PasswordType::class, [
                    'mapped' => false,
                    'label' => false,
                    'required' => false,
                    'attr' => [
                        'placeholder' => 'old password'
                    ]
                ])
                ->add('new_password', RepeatedType::class, [
                    'type' => PasswordType::class,
                    'mapped' => false,
                    'required' => false,
                    'first_options' => [
                        'label' => false,
                    ],
                    'second_options' => [
                        'label' => false,
                    ]
                ]);
            $form->handleRequest($req);
            if ($form->isSubmitted() && $form->isValid()) {
                $profilePicture = $form->get('picture')->getData();
                if ($profilePicture) {
                    // this is needed to safely include the file name as part of the URL
                    $newFilename = uniqid() . '.' . $profilePicture->guessExtension();
                    // Move the file to the directory where pictures are stored
                    try {
                        $profilePicture->move(
                            $this->getParameter('user_picture'),
                            $newFilename
                        );
                    } catch (FileException $e) {
                        // ... handle exception if something happens during file upload
                    }

                    // updates the 'brochureFilename' property to store the PDF file name
                    // instead of its contents
                    $user->setPicture($newFilename);
                }
                $old_password = $form->get('old_password')->getData();
                if ($encoder->isPasswordValid($user, $old_password)) {
                    $new_password = $form->get('new_password')->getData();
                    $password = $encoder->encodePassword($user, $new_password);
                    $user->setPassword($password);
                    $em->flush();
                    return $this->redirectToRoute('user_profile');
                }
            }
            return $this->render('user/edit_profile.html.twig', [
                'form' => $form->createView()
            ]);
        }
    }

    /**
     * @Route("/admin/edit", name="edit_admin")
     */
    public function editAdminAccount(Request $req, UserPasswordEncoderInterface $encoder): Response
    {
        $user = $this->getUser();
        if (!$user) {
            return $this->redirectToRoute('app_login');
        }
        if ($user->getRoles() != ["ROLE_ADMIN"]) {
            return $this->redirectToRoute('home');
        } else {
            $em = $this->getDoctrine()->getManager();
            $form = $this->createForm(RegistrationFormType::class, $user)
                ->add('old_password', PasswordType::class, [
                    'mapped' => false,
                    'label' => false,
                    'required' => false,
                    'attr' => [
                        'placeholder' => 'old password'
                    ]
                ])
                ->add('new_password', RepeatedType::class, [
                    'type' => PasswordType::class,
                    'mapped' => false,
                    'required' => false,
                    'first_options' => [
                        'label' => false,
                    ],
                    'second_options' => [
                        'label' => false,
                    ]
                ]);
            $form->handleRequest($req);
            if ($form->isSubmitted() && $form->isValid()) {
                $profilePicture = $form->get('picture')->getData();
                if ($profilePicture) {
                    // this is needed to safely include the file name as part of the URL
                    $newFilename = uniqid() . '.' . $profilePicture->guessExtension();
                    // Move the file to the directory where pictures are stored
                    try {
                        $profilePicture->move(
                            $this->getParameter('user_picture'),
                            $newFilename
                        );
                    } catch (FileException $e) {
                        // ... handle exception if something happens during file upload
                    }

                    // updates the 'brochureFilename' property to store the PDF file name
                    // instead of its contents
                    $user->setPicture($newFilename);
                }
                $old_password = $form->get('old_password')->getData();
                if ($encoder->isPasswordValid($user, $old_password)) {
                    $new_password = $form->get('new_password')->getData();
                    $password = $encoder->encodePassword($user, $new_password);
                    $user->setPassword($password);
                    $em->flush();
                    return $this->redirectToRoute('admin_profile');
                }
            }
            return $this->render('admin/edit_admin_profile.html.twig', [
                'form' => $form->createView()
            ]);
        }
    }

    /**
     * @Route("/admin/testtttt",name="all_users")
     */
    public function test()
    {}

    /**
     * @Route("user/EventReservationAvecIncrement/{id}/{eventid}/{PrixReservaion}/{userid}", name="EventReservationAvecIncrement", methods={"GET", "POST"})
     */
    public function Increment_PCD_NbrePromo($id, $eventid, $userid, $PrixReservaion, PromoCodeOwnerRepository $PromotionCodeOwnerRepository, \Swift_Mailer $mailer, EntityManagerInterface $entityManager, UserRepository $userRepository, EventRepository $eventRepository): Response
    {
        $user = $this->getUser();
        if (!$user) {
            return $this->redirectToRoute('app_login');
        } else {
            $reservationEvent = new ReservationEvent();
            $event = $eventRepository->findOneBy(['id' => $eventid]);
            $event->setEPlaceReserver($event->getEPlaceReserver() + 1);
            $user = $userRepository->findOneBy(['id' => $userid]);
            $reservationEvent->setUserId($user);
            $reservationEvent->setEventId($event);
            $reservationEvent->setPrixReservationEvent($PrixReservaion);
            $reservationEvent->setDateReservationEvent(new \DateTime('now'));
            $entityManager->persist($reservationEvent);
            $promocodeOwner = $PromotionCodeOwnerRepository->findOneBy(['id' => $id]);
            $promocodeOwner->setPCDNbrePromo($promocodeOwner->getPCDNbrePromo() + 1);

            $message = (new \Swift_Message('ALEMNI, Paiement effectué!'))
                ->setFrom('alemnicontact@gmail.com')
                ->setTo($this->getUser()->getEmail())
                ->setBody(
                    $this->renderView(
                    // templates/emails/registration.html.twig
                        'event/show_event/mail.html.twig',
                        ['nameevent' => $event->getEName(),
                            'firstname' => $this->getUser()->getFirstName(),
                            'lastname' => $this->getUser()->getLastName(),
                            'montant' => $PrixReservaion,

                        ]
                    ),
                    'text/html'
                );

            $mailer->send($message);
            $entityManager->flush();

            return $this->redirectToRoute('show_event');
        }
    }

    /**
     * @Route("user/EventReservation/{eventid}/{PrixReservaion}/{userid}", name="aJouterReservation", methods={"GET", "POST"})
     */
    public function aJouterReservation($eventid, $userid, $PrixReservaion, EntityManagerInterface $entityManager, UserRepository $userRepository, \Swift_Mailer $mailer, EventRepository $eventRepository): Response
    {
        $user = $this->getUser();
        if (!$user) {
            return $this->redirectToRoute('app_login');
        } else {
            $reservationEvent = new ReservationEvent();
            $event = $eventRepository->findOneBy(['id' => $eventid]);
            $user = $userRepository->findOneBy(['id' => $userid]);
            $event->setEPlaceReserver($event->getEPlaceReserver() + 1);
            $reservationEvent->setUserId($user);
            $reservationEvent->setEventId($event);
            $reservationEvent->setPrixReservationEvent($PrixReservaion);
            $reservationEvent->setDateReservationEvent(new \DateTime('now'));
            $entityManager->persist($reservationEvent);

            $message = (new \Swift_Message('ALEMNI, Paiement effectué!'))
                ->setFrom('alemnicontact@gmail.com')
                ->setTo($this->getUser()->getEmail())
                ->setBody(
                    $this->renderView(
                    // templates/emails/registration.html.twig
                        'event/show_event/mail.html.twig',
                        ['nameevent' => $event->getEName(),
                            'firstname' => $this->getUser()->getFirstName(),
                            'lastname' => $this->getUser()->getLastName(),
                            'montant' => $PrixReservaion,

                        ]
                    ),
                    'text/html'
                );

            $mailer->send($message);
            $entityManager->flush();
            return $this->redirectToRoute('show_event');
        }
    }

    /**
     * @Route("/admin/allUsers",name="all_users")
     */
    //TODO: PAGINATOR
    public function getAllUsers(UserRepository $repository, Request $req, PaginatorInterface $paginator): Response
    {
        $user = $this->getUser();
        if (!$user) {
            return $this->redirectToRoute('app_login');
        }
        if ($user->getRoles() == ["ROLE_ADMIN"]) {
            $test = $repository->findAll();
            $pagination = $paginator->paginate(
                $test,
                $req->query->getInt('page', 1),
                5
            );


            $userRepo = $this->getDoctrine()->getRepository(User::class);
            $clients = $userRepo->getUserByRole("ROLE_CLIENT");
            $tutors = $userRepo->getUserByRole("ROLE_TUTOR");
            $admins = $userRepo->getUserByRole("ROLE_ADMIN");
            $both = $userRepo->getUserByRole("ROLE_CLIENT");

            return $this->render('admin/getallusers.html.twig', [
                'clients' => $clients,
                'tutors' => $tutors,
                'admins' => $admins,
                'both' => $both,
                'data' => $pagination,
            ]);
        } else {
            return $this->redirectToRoute('error');
        }
    }

    /**
     * @Route("/admin/ban/{id}",name="ban_user")
     */
    public function banUser($id): Response
    {
        $currentUser = $this->getUser();
        if (!$currentUser) {
            return $this->redirectToRoute('app_login');
        }
        if ($currentUser->getRoles() == ["ROLE_ADMIN"]) {
            $em = $this->getDoctrine()->getManager();
            $user = $this->getDoctrine()->getRepository(User::class)->find($id);
            $user->setIsBanned(true);
            $em->flush();

            return $this->redirectToRoute('all_users');
        } else {
            return $this->redirectToRoute('error');
        }
    }

    /**
     * @Route("/admin/unban/{id}",name="unban_user")
     */
    public function unbanUser($id): Response
    {
        $user = $this->getUser();
        if (!$user) {
            return $this->redirectToRoute('app_login');
        }
        if ($user->getRoles() == ["ROLE_ADMIN"]) {
            $em = $this->getDoctrine()->getManager();
            $user = $this->getDoctrine()->getRepository(User::class)->find($id);
            $user->setIsBanned(false);
            $em->flush();

            return $this->redirectToRoute('all_users');
        } else {
            return $this->redirectToRoute('error');
        }
    }

    /**
     * @Route("/admin/verify/{id}", name="verify_user")
     */
    public function verifyUserAccount($id): Response
    {
        $user = $this->getUser();
        if (!$user) {
            return $this->redirectToRoute('app_login');
        }
        if ($user->getRoles() == ["ROLE_ADMIN"]) {
            $em = $this->getDoctrine()->getManager();
            $user = $this->getDoctrine()->getRepository(User::class)->find($id);
            $user->setIsVerified(true);
            $em->flush();

            return $this->redirectToRoute('all_users');
        } else {
            return $this->redirectToRoute('error');
        }
    }

    /**
     * @Route("/user/delete", name="user_delete")
     */
    public function deleteUser(): Response
    {
        $user = $this->getUser();
        if (!$user) {
            return $this->redirectToRoute('app_login');
        } else {
            $session = $this->get('session');
            $session = new Session();
            $session->invalidate();
            $em = $this->getDoctrine()->getManager();
            $em->remove($user);
            $em->flush();

            return $this->redirectToRoute('app_logout');
        }
    }

    /**
     * @Route("user/addRole", name="second_role")
     */
    public function addNewRole(): Response
    {
        $user = $this->getUser();
        if (!$user) {
            return $this->redirectToRoute('app_login');
        } else {
            $em = $this->getDoctrine()->getManager();
            $user->setRoles(["ROLE_TUTOR", "ROLE_CLIENT"]);
            $user->setIsverified(false);
            $em->flush();

            return $this->redirectToRoute('user_profile');
        }
    }
}
