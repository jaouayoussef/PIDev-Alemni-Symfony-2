<?php

namespace App\Controller;

use App\Form\RegistrationFormType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\Image;
use Symfony\Component\Validator\Constraints\NotBlank;

class UserController extends AbstractController
{
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
