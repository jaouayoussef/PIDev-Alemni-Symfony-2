<?php /** @noinspection ALL */

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Repository\EventRepository;
use App\Repository\ReservationEventRepository;
use App\Repository\ReservationFormationRepository;
use App\Repository\SeanceRepository;
use App\Repository\UserRepository;
use Omines\DataTablesBundle\Column\TextColumn;
use Omines\DataTablesBundle\DataTableFactory;
use Pagerfanta\Doctrine\ORM\QueryAdapter;
use Pagerfanta\Pagerfanta;
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
    public function getUserProfile( ReservationEventRepository $reservationevent,SeanceRepository $seanceRepository,ReservationFormationRepository $reservationFormationRepository): Response
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
     * @Route("/admin/allUsers/{page<\d+>}",name="all_users")
     */
    public function getAllUsers(UserRepository $repository, Request $req, int $page = 1): Response
    {
        $user = $this->getUser();
        if (!$user) {
            return $this->redirectToRoute('app_login');
        }
        if ($user->getRoles() == ["ROLE_ADMIN"]) {
            $test = $repository->createQueryBuilder('user')->select('user');
            $pagerfanta = new Pagerfanta(new QueryAdapter($test));
            $pagerfanta->setMaxPerPage(5);
            $pagerfanta->setCurrentPage($page);

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
                'data' => $pagerfanta,
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
        $user = $this->getUser();
        if (!$user) {
            return $this->redirectToRoute('app_login');
        }
        if ($user->getRoles() == ["ROLE_ADMIN"]) {
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
    //TODO: verify before deleting account
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
    //TODO: verify before changing user role
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

    /**
     * @Route("/admin/dashboard", name="admin_dashboard")
     */
    public function adminDashboard(): Response
    {
        $user = $this->getUser();
        if (!$user) {
            return $this->redirectToRoute('app_login');
        } else {
            return $this->render('admin/dashboard.html.twig');
        }
    }
}
