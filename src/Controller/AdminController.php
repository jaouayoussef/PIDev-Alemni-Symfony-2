<?php

namespace App\Controller;

use App\Repository\UserRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminController extends AbstractController
{
    /**
     * @Route("/admin/user/stats", name="user_stats")
     */
    public function index(UserRepository $userRepository, PaginatorInterface $paginator,Request $request): Response
    {
        $userByDate = $userRepository->findByCreationDate();
        $userByRoles = $userRepository->getUserByRole("ROLE_TUTOR");
        $datePagination = $paginator->paginate(
            $userByDate,
            $request->query->getInt('page',1),
            3
        );
        $rolePagination = $paginator->paginate(
            $userByRoles,
            $request->query->getInt('page',1),
            3
        );
        return $this->render('admin/dashboard.html.twig',[
            'datedUsers' => $datePagination,
            'roledUsers' => $rolePagination
        ]);
    }
}
