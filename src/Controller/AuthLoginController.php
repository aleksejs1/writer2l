<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class AuthLoginController extends AbstractController
{
    /**
     * @Route("/auth/login", name="auth_login")
     */
    public function index()
    {
        return $this->render('auth_login/index.html.twig', [
            'controller_name' => 'AuthLoginController',
        ]);
    }
}
