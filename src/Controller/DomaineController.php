<?php

namespace App\Controller;

use App\Entity\Domaine;
use App\Form\DomaineType;
use App\Repository\DomaineRepository;
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
 * @Route("/domaine")
 */
class DomaineController extends AbstractController
{
   # /**
    # * @Route("/", name="domaine_index", methods={"GET"})
    # */
   # public function index(DomaineRepository $domaineRepository): Response
   # {
    #    return $this->render('domaine/index.html.twig', [
     #       'domaines' => $domaineRepository->findAll(),
     #   ]);
   # }

    /**
     * @Route("/", name="domaine_new", methods={"GET", "POST"})
     */
    //TODO: ONLY ADMIN (BACK)
    public function new(Request $request, EntityManagerInterface $entityManager,DomaineRepository $domaineRepository): Response
    {
        $domaine = new Domaine();
        $form = $this->createForm(DomaineType::class, $domaine)
            ->add('imageDomaine', FileType::class, [
                'mapped' => false,
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
            $image = $form->get('imageDomaine')->getData();

            if ($image) {
                $newFilename = uniqid().'.'.$image->guessExtension();

                try {
                    $image->move(
                        $this->getParameter('image_domaine'),
                        $newFilename
                    );
                } catch (FileException $e) {
                }

                $domaine->setImageDomaine($newFilename);
            }
            $entityManager->persist($domaine);
            $entityManager->flush();

            return $this->redirectToRoute('domaine_new', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('domaine/new.html.twig', [
            'domaine' => $domaine,
            'form' => $form->createView(),
            'domaines' => $domaineRepository->findAll(),
        ]);
    }

    /**
     * @Route("/show", name="domaine_show", )
     */
    //TODO: SHOW ONLY IN FRONT
    public function show(DomaineRepository $domaineRepository): Response
    {
        return $this->render('domaine/show.html.twig', [
            'domaines' => $domaineRepository->findAll(),
        ]);
    }

    /**
     * @Route("/{id}/edit", name="domaine_edit", methods={"GET", "POST"})
     */
    //TODO: ADMIN DOESNT HAVE TO CHANGE THE PICTURE
    public function edit(Request $request, $id,Domaine $domaine, EntityManagerInterface $entityManager,DomaineRepository $domaineRepository): Response
    {
        $form = $this->createForm(DomaineType::class, $domaine)
            ->add('imageDomaine', FileType::class, [
                'mapped' => false,
                'required' => false,
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
            $image = $form->get('imageDomaine')->getData();

            if ($image) {
                $newFilename = uniqid().'.'.$image->guessExtension();

                try {
                    $image->move(
                        $this->getParameter('image_domaine'),
                        $newFilename
                    );
                } catch (FileException $e) {
                }

                $domaine->setImageDomaine($newFilename);
            }
            $entityManager->flush();

            return $this->redirectToRoute('domaine_new', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('domaine/edit.html.twig', [
            'domaine' => $domaine,
            'form' => $form->createView(),
            'domaines' => $domaineRepository->getWhatYouWant($id),
        ]);
    }

    /**
     * @Route("/{id}", name="domaine_delete", methods={"POST"})
     */
    //TODO: TO FIX LATER
    public function delete(Request $request, Domaine $domaine, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$domaine->getId(), $request->request->get('_token'))) {
            $entityManager->remove($domaine);
            $entityManager->flush();
        }

        return $this->redirectToRoute('domaine_new', [], Response::HTTP_SEE_OTHER);
    }
}
