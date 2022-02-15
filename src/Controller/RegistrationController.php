<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Security\AppAuthenticator;
use App\Security\EmailVerifier;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Guard\GuardAuthenticatorHandler;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\Image;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Mime\Address;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Length;
use SymfonyCasts\Bundle\VerifyEmail\Exception\VerifyEmailExceptionInterface;

class RegistrationController extends AbstractController
{
    // private EmailVerifier $emailVerifier;

    // public function __construct(EmailVerifier $emailVerifier)
    // {
    //     $this->emailVerifier = $emailVerifier;
    // }

    // //TODO:CONTROLE DE SAISIE
    // /**
    //  * @Route("/register", name="app_register")
    //  */
    // public function register(Request $request, UserPasswordEncoderInterface $userPasswordEncoder, GuardAuthenticatorHandler $guardHandler, AppAuthenticator $authenticator, EntityManagerInterface $entityManager): Response
    // {
    //     $user = new User();
    //     $form = $this->createForm(RegistrationFormType::class, $user)
    //     ->add('firstName',TextType::class,[
    //         'constraints' => array(
    //             new NotBlank(),
    //             new Length(array('min'=>4))
    //         )
    //     ])
    //     ->add('lastName',TextType::class)
    //     ->add('profilePicture', FileType::class,[
    //         'label' => 'Profile picture',
    //         'required' => true,
    //         'mapped' => false,
    //         'constraints' => [
    //             new Image(),
    //             new NotBlank(),
    //             new File([
    //                 'mimeTypes' => [
    //                     'image/*',
    //                 ],
    //                 'mimeTypesMessage' => 'Please upload a valid picture type',
    //             ])
    //         ]
    //     ])
    //     ->add('phoneNumber',IntegerType::class,array(
    //         'constraints' => array(
    //             new NotBlank(),
    //             new Length(array('min'=>8))
    //         )
    //     ))
    //     ->add('gender',ChoiceType::class, array(
    //         'choices' => array(
    //             'Male'=>'Male',
    //             'Female'=>'Female'
    //         )
    //     ))
    //     ->add('email',EmailType::class,array(
    //         'constraints'=> array(
    //             new NotBlank(),
    //             new Email()
    //         )
    //     ))
    //     ->add('plainPassword', RepeatedType::class,[
    //         'type'=>PasswordType::class,
    //         'mapped'=>false,
    //         'required'=>true,
    //         'first_options'=>[
    //             'label'=>'New password',
    //             'attr'=>[
    //                 'placeholder'=>'...',
    //             ]
    //         ],
    //         'second_options'=>[
    //             'label'=>'Confirm password',
    //             'attr'=>[
    //                 'placeholder'=>'...'
    //             ]
    //         ]
    //     ]);
    //     $form->handleRequest($request);

    //     if ($form->isSubmitted() && $form->isValid()) {
    //         $profilePicture = $form->get('profilePicture')->getData();
    //         if ($profilePicture) {
    //             // this is needed to safely include the file name as part of the URL
    //             $newFilename = uniqid().'.'.$profilePicture->guessExtension();
    //             // Move the file to the directory where pictures are stored
    //             try {
    //                 $profilePicture->move(
    //                     $this->getParameter('profile_picture_dir'),
    //                     $newFilename
    //                 );
    //             } catch (FileException $e) {
    //                 // ... handle exception if something happens during file upload
    //             }

    //             // updates the 'brochureFilename' property to store the PDF file name
    //             // instead of its contents
    //             $user->setProfilePicture($newFilename);
    //         }

    //         // encode the plain password
    //         $user->setPassword(
    //         $userPasswordEncoder->encodePassword(
    //                 $user,
    //                 $form->get('plainPassword')->getData()
    //             )
    //         );

    //         $user->setCreationDate(new \DateTime());

    //         $entityManager->persist($user);
    //         $entityManager->flush();

    //         //generate a signed url and email it to the user
    //         $this->emailVerifier->sendEmailConfirmation('app_verify_email', $user,
    //             (new TemplatedEmail())
    //                 ->from(new Address('verify@alemni.com', 'Alemni'))
    //                 ->to($user->getEmail())
    //                 ->subject('Please Confirm your Email')
    //                 ->htmlTemplate('registration/confirmation_email.html.twig')
    //         );
    //         //do anything else you need here, like send an email

    //         return $guardHandler->authenticateUserAndHandleSuccess(
    //             $user,
    //             $request,
    //             $authenticator,
    //             'main' // firewall name in security.yaml
    //         );
    //     }

    //     return $this->render('registration/register.html.twig', [
    //         'registrationForm' => $form->createView(),
    //     ]);
    // }

    // //TODO: VERIFY PASSWORD WITH EMAIL USING GMAIL
    // /**
    //  * @Route("/verify/email", name="app_verify_email")
    //  */
    // public function verifyUserEmail(Request $request): Response
    // {
    //     $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

    //     // validate email confirmation link, sets User::isVerified=true and persists
    //     try {
    //         $this->emailVerifier->handleEmailConfirmation($request, $this->getUser());
    //     } catch (VerifyEmailExceptionInterface $exception) {
    //         $this->addFlash('verify_email_error', $exception->getReason());

    //         return $this->redirectToRoute('homepage');
    //     }

    //     // @TODO Change the redirect on success and handle or remove the flash message in your templates
    //     $this->addFlash('success', 'Your email address has been verified.');

    //     return $this->redirectToRoute('user_profile');
    // }
}
