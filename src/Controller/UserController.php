<?php

namespace App\Controller;

use App\Form\UserType;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 *
 * Class UserController
 * @package App\Controller
 */
class UserController extends AbstractFOSRestController
{
    /**
     * @Rest\Get("/api/v1/user")
     * @Rest\View()
     */
    public function getCurrentUser()
    {
        $user = $this->getUser();
        $view = $this->view($user, 200);
        return $this->handleView($view);
    }

    /**
     * @Rest\Post("/register")
     * @param Request $request
     * @param UserPasswordEncoderInterface $passwordEncoder
     * @return Response
     */
    public function register(Request $request, UserPasswordEncoderInterface $passwordEncoder)
    {
        $form = $this->createForm(UserType::class);
        $form->submit($request->request->all());

        if (false === $form->isValid()) {
            return $this->handleView($this->view($form));
        }

        $hashedPassword =
            $passwordEncoder->encodePassword(
                $form->getData(),
                $form->getData()->getPassword()
            );
        $form->getData()->setPassword($hashedPassword);
        $this->getDoctrine()->getManager()->persist($form->getData());
        $this->getDoctrine()->getManager()->flush();

        return $this->handleView($this->view([
            'status' => 'ok'
        ],
            Response::HTTP_CREATED
        ));
    }
}