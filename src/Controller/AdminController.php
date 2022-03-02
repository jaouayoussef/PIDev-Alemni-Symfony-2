<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\PromoCodeOwnerRepository;
use App\Repository\PromotionCodeRepository;

/**
 * @Route("/admin")
 */
class AdminController extends AbstractController
{
    /**
     * @Route("/stat", name="stat")
     */
    public function index(PromoCodeOwnerRepository $codePromoOwner , PromotionCodeRepository $codePromo): Response
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
