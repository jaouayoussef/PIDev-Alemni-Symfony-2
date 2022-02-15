<?php

namespace App\Controller;

use App\Form\RegistrationFormType;

use App\Entity\User;
use KnpU\OAuth2ClientBundle\Client\ClientRegistry;
use App\Security\AppAuthenticator;
use App\Security\EmailVerifier;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Client;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\FrameworkBundle\Controller\TemplateController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Guard\GuardAuthenticatorHandler;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Mime\Address;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Length;
use SymfonyCasts\Bundle\VerifyEmail\Exception\VerifyEmailExceptionInterface;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\Image;
use Symfony\Component\Validator\Constraints\NotBlank;

class UserController extends AbstractController
{

    private EmailVerifier $emailVerifier;


    public function __construct(EmailVerifier $emailVerifier)
    {
        $this->emailVerifier = $emailVerifier;
    }

    

    //TODO:CONTROLE DE SAISIE
    /**
     * @Route("/register", name="app_register")
     */
    public function register(Request $request, UserPasswordEncoderInterface $userPasswordEncoder, GuardAuthenticatorHandler $guardHandler, AppAuthenticator $authenticator, EntityManagerInterface $entityManager): Response
    {
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user)
        ->add('firstName',TextType::class,[
            'constraints' => array(
                new NotBlank(),
                new Length(array('min'=>4))
            )
        ])
        ->add('lastName',TextType::class)
        ->add('profilePicture', FileType::class,[
            'label' => 'Profile picture',
            'required' => true,
            'mapped' => false,
            'constraints' => [
                new Image(),
                new NotBlank(),
                new File([
                    'mimeTypes' => [
                        'image/*',
                    ],
                    'mimeTypesMessage' => 'Please upload a valid picture type',
                ])
            ]
        ])
        ->add('phoneNumber',IntegerType::class,array(
            'constraints' => array(
                new NotBlank(),
                new Length(array('min'=>8))
            )
        ))
        ->add('gender',ChoiceType::class, array(
            'choices' => array(
                'Male'=>'Male',
                'Female'=>'Female'
            )
        ))
        ->add('email',EmailType::class,array(
            'constraints'=> array(
                new NotBlank(),
                new Email()
            )
        ))
        ->add('plainPassword', RepeatedType::class,[
            'type'=>PasswordType::class,
            'mapped'=>false,
            'required'=>true,
            'first_options'=>[
                'label'=>'New password',
                'attr'=>[
                    'placeholder'=>'...',
                ]
            ],
            'second_options'=>[
                'label'=>'Confirm password',
                'attr'=>[
                    'placeholder'=>'...'
                ]
            ]
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $profilePicture = $form->get('profilePicture')->getData();
            if ($profilePicture) {
                // this is needed to safely include the file name as part of the URL
                $newFilename = uniqid().'.'.$profilePicture->guessExtension();
                // Move the file to the directory where pictures are stored
                try {
                    $profilePicture->move(
                        $this->getParameter('profile_picture_dir'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }

                // updates the 'brochureFilename' property to store the PDF file name
                // instead of its contents
                $user->setProfilePicture($newFilename);
            }

            // encode the plain password
            $user->setPassword(
            $userPasswordEncoder->encodePassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );

            $user->setCreationDate(new \DateTime());

            $entityManager->persist($user);
            $entityManager->flush();

            //generate a signed url and email it to the user
            $this->emailVerifier->sendEmailConfirmation('app_verify_email', $user,
                (new TemplatedEmail())
                    ->from(new Address('verify@alemni.com', 'Alemni'))
                    ->to($user->getEmail())
                    ->subject('Please Confirm your Email')
                    ->htmlTemplate('registration/confirmation_email.html.twig')
            );
            //do anything else you need here, like send an email

            return $guardHandler->authenticateUserAndHandleSuccess(
                $user,
                $request,
                $authenticator,
                'main' // firewall name in security.yaml
            );
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }

    /**
     * @Route("/finishReg", name="next_reg")
     */
    public function completeRegistration(Request $request, UserPasswordEncoderInterface $userPasswordEncoder, GuardAuthenticatorHandler $guardHandler, AppAuthenticator $authenticator, EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();
        $form = $this->createForm(RegistrationFormType::class, $user)
        ->add('firstName',TextType::class,[
            'constraints' => array(
                new NotBlank(),
                new Length(array('min'=>4))
            )
        ])
        ->add('lastName',TextType::class)
        ->add('profilePicture', FileType::class,[
            'label' => 'Profile picture',
            'required' => true,
            'mapped' => false,
            'constraints' => [
                new Image(),
                new NotBlank(),
                new File([
                    'mimeTypes' => [
                        'image/*',
                    ],
                    'mimeTypesMessage' => 'Please upload a valid picture type',
                ])
            ]
        ])
        ->add('phoneNumber',IntegerType::class,array(
            'constraints' => array(
                new NotBlank(),
                new Length(array('min'=>8))
            )
        ))
        ->add('gender',ChoiceType::class, array(
            'choices' => array(
                'Male'=>'Male',
                'Female'=>'Female'
            )
        ))
        ->add('plainPassword', RepeatedType::class,[
            'type'=>PasswordType::class,
            'mapped'=>false,
            'required'=>true,
            'first_options'=>[
                'label'=>'New password',
                'attr'=>[
                    'placeholder'=>'...',
                ]
            ],
            'second_options'=>[
                'label'=>'Confirm password',
                'attr'=>[
                    'placeholder'=>'...'
                ]
            ]
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $profilePicture = $form->get('profilePicture')->getData();
            if ($profilePicture) {
                // this is needed to safely include the file name as part of the URL
                $newFilename = uniqid().'.'.$profilePicture->guessExtension();
                // Move the file to the directory where pictures are stored
                try {
                    $profilePicture->move(
                        $this->getParameter('profile_picture_dir'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }

                // updates the 'brochureFilename' property to store the PDF file name
                // instead of its contents
                $user->setProfilePicture($newFilename);
            }

            // encode the plain password
            $user->setPassword(
            $userPasswordEncoder->encodePassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );

            $user->setCreationDate(new \DateTime());

            $entityManager->persist($user);
            $entityManager->flush();

            return $guardHandler->authenticateUserAndHandleSuccess(
                $user,
                $request,
                $authenticator,
                'main' // firewall name in security.yaml
            );
        }
        return $this->render('registration/add_password.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }

    /**
     * @Route("/connect/github", name="github_connect")
     */
    public function connect(ClientRegistry $clientRegistry) : RedirectResponse
    {
        $client = $clientRegistry->getClient('github');
        return $client->redirect(['read:user','user:email']);
    }

    //TODO: VERIFY PASSWORD WITH EMAIL USING GMAIL
    /**
     * @Route("/verify/email", name="app_verify_email")
     */
    public function verifyUserEmail(Request $request): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        // validate email confirmation link, sets User::isVerified=true and persists
        try {
            $this->emailVerifier->handleEmailConfirmation($request, $this->getUser());
        } catch (VerifyEmailExceptionInterface $exception) {
            $this->addFlash('verify_email_error', $exception->getReason());

            return $this->redirectToRoute('homepage');
        }

        // @TODO Change the redirect on success and handle or remove the flash message in your templates
        $this->addFlash('success', 'Your email address has been verified.');

        return $this->redirectToRoute('user_profile');
    }

    /**
     * @Route("/login", name="app_login")
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // if ($this->getUser()) {
        //     return $this->redirectToRoute('target_path');
        // }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    /**
     * @Route("/home", name="homepage")
     */
    public function homepage(): Response
    {
        return $this->render('security/home.html.twig');
    }

    //TODO: CONTROLE BEFORE DELETING ACCOUNT
    /**
     * @Route("/delete", name="delete_account")
     */
    public function deleteAccount(): Response
    {
        $user = $this->getUser();
        $session = $this->get('session');
        $session = new Session();
        $session->invalidate();
        $em = $this->getDoctrine()->getManager();
        $em->remove($user);
        $em->flush();

        return $this->redirectToRoute('app_logout');
    }

    //TODO:CONTROLE DE SAISIE
    /**
     * @Route("/user/edit", name="edit_account")
     */
    public function editAccount(Request $req, UserPasswordEncoderInterface $encoder): Response
    {
        $user = $this->getUser();
        $em = $this->getDoctrine()->getManager();
        $form = $this->createForm(RegistrationFormType::class,$user)
        ->add('firstName', TextType::class ,  ['disabled' => true ])
        ->add('lastName', TextType::class ,  ['disabled' => true ])
        ->add('email', TextType::class ,  ['disabled' => true ])
        ->add('profilePicture', FileType::class,[
            'label' => 'Profile picture',
            'mapped' => false,
            'constraints' => [
                new Image(),
                new NotBlank(),
                new File([
                    'mimeTypes' => [
                        'image/*',
                    ],
                    'mimeTypesMessage' => 'Please upload a valid picture type',
                ])
            ]
        ])
        ->add('phoneNumber',NumberType::class, ['disabled' => true])
        ->add('old_password', PasswordType::class,['mapped'=>false,'attr'=>['placeholder'=>'old password']])
        ->add('new_password',RepeatedType::class,[
            'type'=>PasswordType::class,
            'mapped'=>false,
            'required'=>true,
            'first_options'=>[
                'label'=>'New password',
                'attr'=>[
                    'placeholder'=>'...',
                ]
            ],
            'second_options'=>[
                'label'=>'Confirm password',
                'attr'=>[
                    'placeholder'=>'...',
                ]
            ]
        ]);
        $form->handleRequest($req);
        if ($form->isSubmitted()&&$form->isValid())
        {
            $profilePicture = $form->get('profilePicture')->getData();
            if ($profilePicture) {
                // this is needed to safely include the file name as part of the URL
                $newFilename = uniqid().'.'.$profilePicture->guessExtension();
                // Move the file to the directory where pictures are stored
                try {
                    $profilePicture->move(
                        $this->getParameter('profile_picture_dir'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }

                // updates the 'brochureFilename' property to store the PDF file name
                // instead of its contents
                $user->setProfilePicture($newFilename);
            }
            $old_password = $form->get('old_password')->getData();
            if($encoder->isPasswordValid($user,$old_password)){
                $new_password = $form->get('new_password')->getData();
                $password = $encoder->encodePassword($user,$new_password);
                $user->setPassword($password);
                $em->flush();
                return $this->redirectToRoute('user_profile');   
            }
        }
        return $this->render('security/edit.html.twig', [
            'form' => $form->createView()
            ]);
    }

    /**
     * @Route("/profile", name="user_profile")
     */
    public function viewProfile(): Response
    {
        return $this->render('security/profile.html.twig');
    }


    /**
     * @Route("/logout", name="app_logout")
     */
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }
}
