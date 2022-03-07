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
        $user = $this->getUser();
        if(!$user){
            return $this->redirectToRoute('app_login');
        }
        else if($user->getRoles() == "ROLE_ADMIN"){
            $users = $userRepository->findAll();
            $nbAdmins = 0;
            $nbTutors = 0;
            $nbClients = 0;
            $nbBanned = 0;

            foreach ($users as $user)
            {
                if ($user->getRoles()[0]=="ROLE_ADMIN")
                {
                    $nbAdmins++;
                }
                elseif ($user->getRoles()[0]=="ROLE_TUTOR")
                {
                    $nbTutors++;
                }
                elseif ($user->getRoles()[0]=="ROLE_CLIENT")
                {
                    $nbClients++;
                }
                if($user->getIsBanned() === true){
                    $nbBanned++;
                }
            }

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
                'roledUsers' => $rolePagination,
                "nbAdmins" => $nbAdmins,
                "nbClients" => $nbClients,
                "nbBanned" => $nbBanned,
                "nbTutors" => $nbTutors,
            ]);
        }
        else{
            return $this->redirectToRoute('error');
        }

    }
}
