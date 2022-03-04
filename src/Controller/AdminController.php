<?php

namespace App\Controller;

use App\Repository\PromoCodeOwnerRepository;
use App\Repository\PromotionCodeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminController extends AbstractController
{
    /**
     * @Route("/admin", name="admin")
     */
    public function index(): Response
    {
        return $this->render('admin/dashboard.html.twig');
    }

    /**
     * @Route("/admin/stat", name="stat")
     */
    public function stats(PromoCodeOwnerRepository $codePromoOwner , PromotionCodeRepository $codePromo): Response
    {
        $codePromOwners = $codePromoOwner->findAll();
        $codePromoOwner = [];
        $codePromoCount = [];
        foreach ($codePromOwners as $codeProm){
            $codePromoOwner[] = $codeProm->getPCDEmail();
            $codePromoCount[] = count($codeProm->getPCDPromotionCode());
        }

        $CodePromos = $codePromo->CountBydate();
        $dates=[];
        $PromoCodesCount=[];
        foreach ($CodePromos as $CodePromo){
            $dates[] =  $CodePromo['datePromo'];
            $PromoCodesCount[] =  $CodePromo['count'];
        }

        return $this->render('admin/index.html.twig',[
            'codeProms' => json_encode($codePromoOwner),
            'codePromoCount' => json_encode($codePromoCount),
            'codePromoDate' => json_encode($dates),
            'CodeCount' => json_encode($PromoCodesCount),
        ]);
    }
}
