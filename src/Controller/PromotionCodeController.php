<?php

namespace App\Controller;

use App\Entity\Promotion;
use App\Entity\PromotionCode;
use App\Form\PromotionCodeType;
use App\Form\PromotionType;
use App\Repository\PromotionCodeRepository;
use App\Repository\PromotionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/promotioncode")
 */
class PromotionCodeController extends AbstractController
{
    #/**
    # * @Route("/", name="promotion_code_index", methods={"GET"})
    # */
    #public function index(PromotionCodeRepository $promotionCodeRepository): Response
    #{
    #    return $this->render('promotion_code/index.html.twig', [
    #        'promotion_codes' => $promotionCodeRepository->findAll(),
    #    ]);
    #}

    /**
     * @Route("/", name="promotion_code_new", methods={"GET", "POST"})
     */
    public function new(Request $request, EntityManagerInterface $entityManager, PromotionCodeRepository $promotionCodeRepository, PromotionRepository $promotionRepository): Response
    {
        $user = $this->getUser();
        if (!$user) {
            return $this->redirectToRoute('app_login');
        } else if ($user->getRoles() == "ROLE_ADMIN") {
            $promotionCode = new PromotionCode();
            $promotion = new Promotion();
            $form = $this->createForm(PromotionCodeType::class, $promotionCode);
            $form1 = $this->createForm(PromotionType::class, $promotion);
            $form->handleRequest($request);
            $form1->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $entityManager->persist($promotionCode);
                $entityManager->flush();

                return $this->redirectToRoute('promotion_code_new', [], Response::HTTP_SEE_OTHER);
            }
            if ($form1->isSubmitted() && $form1->isValid()) {
                $entityManager->persist($promotion);
                $entityManager->flush();

                return $this->redirectToRoute('promotion_code_new', [], Response::HTTP_SEE_OTHER);
            }
            $CodePromos = $promotionCodeRepository->CountBydate();
            $dates = [];
            $PromoCodesCount = [];
            foreach ($CodePromos as $CodePromo) {
                $dates[] = $CodePromo['datePromo'];
                $PromoCodesCount[] = $CodePromo['count'];
            }
            $Promos = $promotionRepository->CountBydatePromo();
            $datesPromo = [];
            $PromosCount = [];
            foreach ($Promos as $Promo) {
                $datesPromo[] = $Promo['datePromo'];
                $PromosCount[] = $Promo['count'];
            }
            return $this->render('promotion_code/new.html.twig', [
                'promotion_code' => $promotionCode,
                'promotion' => $promotion,
                'form' => $form->createView(),
                'form1' => $form1->createView(),
                'promotion_codes' => $promotionCodeRepository->findAll(),
                'promotions' => $promotionRepository->getPromotionbydatenow(),
                'codePromoDate' => json_encode($dates),
                'CodeCount' => json_encode($PromoCodesCount),
                'PromoDate' => json_encode($datesPromo),
                'PromoCount' => json_encode($PromosCount),
            ]);
        } else {
            return $this->redirectToRoute('error');
        }

    }

    /*/**
     * @Route("/{id}", name="promotion_code_show", methods={"GET"})
     */
    /*public function show(PromotionCode $promotionCode): Response
    {
        return $this->render('promotion_code/show.html.twig', [
            'promotion_code' => $promotionCode,
        ]);
    }*/

    /**
     * @Route("/{id}/edit", name="promotion_code_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, $id, PromotionCode $promotionCode, EntityManagerInterface $entityManager, PromotionCodeRepository $promotionCodeRepository): Response
    {
        $user = $this->getUser();
        if (!$user) {
            return $this->redirectToRoute('app_login');
        } else if ($user->getRoles() == "ROLE_ADMIN") {
            $form = $this->createForm(PromotionCodeType::class, $promotionCode);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $entityManager->flush();

                return $this->redirectToRoute('promotion_code_new', [], Response::HTTP_SEE_OTHER);
            }

            return $this->render('promotion_code/edit.html.twig', [
                'promotion_code' => $promotionCode,
                'form' => $form->createView(),
                'promotion_codes' => $promotionCodeRepository->getWhatYouWantPromotionCode($id),

            ]);
        } else {
            return $this->redirectToRoute('error');
        }

    }

    /**
     * @Route("/{id}", name="promotion_code_delete", methods={"POST"})
     */
    public function delete(Request $request, PromotionCode $promotionCode, EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();
        if (!$user) {
            return $this->redirectToRoute('app_login');
        } else if ($user->getRoles() == "ROLE_ADMIN") {
            if ($this->isCsrfTokenValid('delete' . $promotionCode->getId(), $request->request->get('_token'))) {
                $entityManager->remove($promotionCode);
                $entityManager->flush();
            }

            return $this->redirectToRoute('promotion_code_new', [], Response::HTTP_SEE_OTHER);
        } else {
            return $this->redirectToRoute('error');
        }

    }
}
