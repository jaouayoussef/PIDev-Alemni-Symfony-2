<?php

namespace App\Controller;

use App\Entity\PromoCodeOwner;
use App\Form\PromoCodeOwnerType;
use App\Repository\PromoCodeOwnerRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;


/**
 * @Route("/promocodeowner")
 */
class PromoCodeOwnerController extends AbstractController
{
    /*/**
     * @Route("/show", name="promo_code_owner_index", methods={"GET"})
     */
    /*public function index(PromoCodeOwnerRepository $promoCodeOwnerRepository , NormalizerInterface $normalizer): Response
    {
        $p = $promoCodeOwnerRepository->findAll();
        $json=$normalizer->normalize($p,'json',['groups'=>'romoodewners']);
        return new Response(json_encode($json));
    }*/

    /**
     * @Route("/", name="promo_code_owner_new", methods={"GET", "POST"})
     */
    public function new(Request $request, EntityManagerInterface $entityManager, PromoCodeOwnerRepository $promoCodeOwnerRepository, PromoCodeOwnerRepository $codePromoOwner): Response
    {
        $user = $this->getUser();
        if (!$user) {
            return $this->redirectToRoute('app_login');
        } else if ($user->getRoles() == ["ROLE_ADMIN"]) {
            $promoCodeOwner = new PromoCodeOwner();
            $form = $this->createForm(PromoCodeOwnerType::class, $promoCodeOwner);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $entityManager->persist($promoCodeOwner);
                $entityManager->flush();
                return $this->redirectToRoute('promo_code_owner_new', [], Response::HTTP_SEE_OTHER);
            }
            $codePromOwners = $codePromoOwner->findAll();
            $codePromoOwner = [];
            $codePromoCount = [];
            foreach ($codePromOwners as $codeProm) {
                $codePromoOwner[] = $codeProm->getPCDEmail();
                $codePromoCount[] = count($codeProm->getPCDPromotionCode());
            }
            $codePromoCountnbrePromo = [];
            foreach ($codePromOwners as $codeProm) {
                $codePromoCountnbrePromo[] = $codeProm->getPCDNbrePromo();
            }
            return $this->render('promo_code_owner/new.html.twig', [
                'promo_code_owner' => $promoCodeOwner,
                'form' => $form->createView(),
                'promo_code_owners' => $promoCodeOwnerRepository->findAll(),
                'codeProms' => json_encode($codePromoOwner),
                'codePromoCount' => json_encode($codePromoCount),
                'codePromoCountnbrePromo' => json_encode($codePromoCountnbrePromo),
            ]);
        } else {
            return $this->redirectToRoute('error');
        }
    }
    /*/**
     * @Route("/Add", name="promo_code_owner_Add", methods={"GET", "POST"})
     */
    /*public function AddPromoCodeOwner (Request $request, SerializerInterface $serializer,EntityManagerInterface $entityManager ): Response
    {
        $content = $request->getContent();
        dd($content);
        $data=$serializer->deserialize($content,PromoCodeOwner::class,'json');
        $entityManager->persist($data);
        $entityManager->flush();
        return new Response('PromoCodeOwner added successfully');
    }*/
    /*/**
     * @Route("/{id}", name="promo_code_owner_show", methods={"GET"})
     */
    /*public function show(PromoCodeOwner $promoCodeOwner): Response
    {
        return $this->render('promo_code_owner/show.html.twig', [
            'promo_code_owner' => $promoCodeOwner,
        ]);
    }*/

    /**
     * @Route("/{id}/edit", name="promo_code_owner_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, PromoCodeOwner $promoCodeOwner, EntityManagerInterface $entityManager, PromoCodeOwnerRepository $promoCodeOwnerRepository, $id): Response
    {
        $user = $this->getUser();
        if (!$user) {
            return $this->redirectToRoute('app_login');
        } else if ($user->getRoles() == ["ROLE_ADMIN"]) {
            $form = $this->createForm(PromoCodeOwnerType::class, $promoCodeOwner);
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                $entityManager->flush();
                return $this->redirectToRoute('promo_code_owner_new', [], Response::HTTP_SEE_OTHER);
            }
            return $this->render('promo_code_owner/edit.html.twig', [
                'promo_code_owner' => $promoCodeOwner,
                'form' => $form->createView(),
                'promo_code_owners' => $promoCodeOwnerRepository->getWhatYouWant($id),
            ]);
        } else {
            return $this->redirectToRoute('error');
        }

    }

    /**
     * @Route("/{id}", name="promo_code_owner_delete", methods={"POST"})
     */
    public function delete(Request $request, PromoCodeOwner $promoCodeOwner, EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();
        if (!$user) {
            return $this->redirectToRoute('app_login');
        } else if ($user->getRoles() == ["ROLE_ADMIN"]) {
            if ($this->isCsrfTokenValid('delete' . $promoCodeOwner->getId(), $request->request->get('_token'))) {
                $entityManager->remove($promoCodeOwner);
                $entityManager->flush();
            }

            return $this->redirectToRoute('promo_code_owner_new', [], Response::HTTP_SEE_OTHER);
        } else {
            return $this->redirectToRoute('error');
        }
    }
}
