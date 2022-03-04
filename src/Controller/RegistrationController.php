<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Security\Authenticator;
use Doctrine\ORM\EntityManagerInterface;
use Gregwar\CaptchaBundle\Type\CaptchaType;
use Swift_Mailer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Guard\GuardAuthenticatorHandler;
use Symfony\Component\Validator\Constraints\NotBlank;

class RegistrationController extends AbstractController
{
    /**
     * @Route("/admin/register", name="admin_register")
     */
    public function registerAdmin(Request $request, UserPasswordEncoderInterface $userPasswordEncoder, GuardAuthenticatorHandler $guardHandler, Authenticator $authenticator, EntityManagerInterface $entityManager): Response
    {
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user)
            ->add('plainPassword', RepeatedType::class, [
                'type' => PasswordType::class,
                'required' => false,
                'mapped' => false,
                'first_options' => [
                    'label' => false,
                    'attr' => [
                        'placeholder' => '...',
                    ],
                    'constraints' => [
                        new NotBlank(),
                    ]
                ],
                'second_options' => [
                    'label' => false,
                    'attr' => [
                        'placeholder' => '...'
                    ],
                    'constraints' => [
                        new NotBlank(),
                    ]
                ]
            ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $picture = $form->get('picture')->getData();
            if ($picture) {
                // this is needed to safely include the file name as part of the URL
                $newFilename = uniqid() . '.' . $picture->guessExtension();
                // Move the file to the directory where pictures are stored
                try {
                    $picture->move(
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

            // encode the plain password
            $user->setPassword(
                $userPasswordEncoder->encodePassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );
            $user->setRoles(["ROLE_ADMIN"]);
            $user->setIsVerified(true);
            $user->setCreatedAt(new \DateTimeImmutable());

            $entityManager->persist($user);
            $entityManager->flush();

            //generate a signed url and email it to the user
            //do anything else you need here, like send an email

            return $guardHandler->authenticateUserAndHandleSuccess(
                $user,
                $request,
                $authenticator,
                'main' // firewall name in security.yaml
            );
        }

        return $this->render('admin/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }

    /**
     * @Route("/user/register", name="user_register")
     */
    public function register(Request $request, Swift_Mailer $mailer, UserPasswordEncoderInterface $userPasswordEncoder, GuardAuthenticatorHandler $guardHandler, Authenticator $authenticator, EntityManagerInterface $entityManager): Response
    {
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user)
            ->add('captcha', CaptchaType::class, array(
                'width' => 200,
                'height' => 50,
                'length' => 6,
                'quality' => 90,
                'distortion' => true,
                'background_color' => [115, 194, 251],
                'max_front_lines' => 0,
                'max_behind_lines' => 0,
                'label' => false,
                'attr' => array('class' => 'form-control',
                    'rows' => "6"
                )))
            ->add('verificationFile', FileType::class, [
                'label' => false,
                'required' => false,
                'mapped' => false,
            ])
            ->add('roles', ChoiceType::class, array(
                'label' => false,
                'expanded' => true,
                'required' => false,
                'multiple' => true,
                'choices' => array(
                    'Client' => 'ROLE_CLIENT',
                    'Tutor' => 'ROLE_TUTOR'
                ),
                'constraints' => [
                    new NotBlank()
                ]
            ))
            ->add('plainPassword', RepeatedType::class, [
                'type' => PasswordType::class,
                'mapped' => false,
                'required' => false,
                'first_options' => [
                    'label' => false,
                    'attr' => [
                        'placeholder' => '...',
                    ],
                    'constraints' => [
                        new NotBlank(),
                    ]
                ],
                'second_options' => [
                    'label' => false,
                    'attr' => [
                        'placeholder' => '...'
                    ],
                    'constraints' => [
                        new NotBlank(),
                    ]
                ]
            ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $picture = $form->get('picture')->getData();
            $verif = $form->get('verificationFile')->getData();
            $role = $form->get('roles')->getData();
            if ($picture) {
                // this is needed to safely include the file name as part of the URL
                $newFilename = uniqid() . '.' . $picture->guessExtension();
                // Move the file to the directory where pictures are stored
                try {
                    $picture->move(
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
            if ($verif) {
                // this is needed to safely include the file name as part of the URL
                $newFilename = uniqid() . '.' . $verif->guessExtension();
                // Move the file to the directory where pictures are stored
                try {
                    $verif->move(
                        $this->getParameter('verification_file'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }

                // updates the 'brochureFilename' property to store the PDF file name
                // instead of its contents
                $user->setVerificationFile($newFilename);
            }
            if ($role == ["ROLE_CLIENT"]) {
                $user->setIsVerified(true);
            } else {
                $user->setIsVerified(false);
            }

            // encode the plain password
            $user->setPassword(
                $userPasswordEncoder->encodePassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );

            $user->setCreatedAt(new \DateTimeImmutable());

            $entityManager->persist($user);
            $entityManager->flush();

            //generate a signed url and email it to the user
            //do anything else you need here, like send an email
            $message = (new \Swift_Message('Welcome to Alemni'))
                ->setFrom('Alemnicontact@gmail.com')
                ->setTo($user->getEmail());
            $img = $message->embed(\Swift_Image::fromPath('Back/assets/email/images/LogoPi.png'));
            $message->setBody(
                $this->renderView(
                    'emails/registration.html.twig',
                    [
                        'img' => $img
                    ]
                ),
                'text/html'
            );

            $mailer->send($message);

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
}
