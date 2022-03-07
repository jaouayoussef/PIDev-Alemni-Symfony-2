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
use Symfony\Component\Serializer\SerializerInterface;
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
     * @Route("/new", name="domaine_new", methods={"GET", "POST"})
     */
    public function new(Request $request, EntityManagerInterface $entityManager,DomaineRepository $domaineRepository): Response
    {
        $domaine = new Domaine();
        $form = $this->createForm(DomaineType::class, $domaine)    ->add('imageDomaine', FileType::class, [


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
    //TODO: SHOW DATA IN FRONT (Not displaying : base-front problem)
    public function show(DomaineRepository $domaineRepository): Response
    {
        return $this->render('domaine/show.html.twig', [
            'domaines' => $domaineRepository->findAll(),
        ]);
    }

    /**
     * @Route("/{id}/edit", name="domaine_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, $id,Domaine $domaine, EntityManagerInterface $entityManager,DomaineRepository $domaineRepository): Response
    {
        $form = $this->createForm(DomaineType::class, $domaine)    ->add('imageDomaine', FileType::class, [


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
    public function delete(Request $request, Domaine $domaine, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$domaine->getId(), $request->request->get('_token'))) {
            $entityManager->remove($domaine);
            $entityManager->flush();
        }

        return $this->redirectToRoute('domaine_new', [], Response::HTTP_SEE_OTHER);
    }

    //TODO: NOT INCLUDED
    /**
     * @Route("/liste",name="liste")
     */
    public function getDomaines(DomaineRepository $domaineRepository,SerializerInterface $serializerInterface, EntityManagerInterface $entityManager){
        $domaines=$domaineRepository->findAll();

        $json=$serializerInterface->serialize($domaines,'json',['groups'=>'domaine']);
        return new response ($json);

    }

    //TODO: NOT INCLUDED
    /**
     * @Route("/add",name="add")
     */
    public function addDomaine(Request $request,SerializerInterface $serializerInterface, EntityManagerInterface $entityManager){
        $content=$request->getContent();
        $data=$serializerInterface->deserialize($content,Domaine::class,'json');
        $entityManager->persist($data);
        $entityManager->flush();
        return new Response('domaine ajouté avec succés');

    }
}
