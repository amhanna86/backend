<?php

namespace App\Controller;

use App\Entity\User;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/api/v1")
 *
 * Class UserController
 * @package App\Controller
 */

class UserController extends AbstractFOSRestController
{
    /**
     * @Rest\Get("/users")
     * @Rest\View()
     */
    public function getUsers(){
        $users = $this->getDoctrine()->getRepository(User::class)->findAll();
        $view = $this->view($users, 200);
        return $this->handleView($view);
    }
}