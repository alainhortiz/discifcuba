<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class SecurityController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction()
    {
        $authUtils = $this->get('security.authentication_utils');
        $error = $authUtils->getLastAuthenticationError();
        $lastUsername = $authUtils->getLastUsername();

        // replace this example code with whatever you need
        return $this->render('Inicio/login.html.twig',
            array(
                'last_username' => $lastUsername,
                'error' => $error,
            ));
    }

    /**
     * @Route("/login", name="login")
     */
    public function loginAction()
    {
        $authUtils = $this->get('security.authentication_utils');
        $error = $authUtils->getLastAuthenticationError();
        $lastUsername = $authUtils->getLastUsername();

        return $this->render('Inicio/login.html.twig',
            array(
                'last_username' => $lastUsername,
                'error' => $error,
            ));
    }

    /**
     * @Route("/login_check", name="login_check")
     */
    public function login_checkAction()
    {
    }

    /**
     * @Route("/logout", name="logout")
     */
    public function logoutAction()
    {
    }

    /**
     * @Route("/passwordForm", name="passwordForm")
     */
    public function passwordFormAction()
    {
        return $this->render('Nomencladores/Usuarios/cambiarPassword.html.twig');
    }

    /**
     * @Route("/changePassword", name="changePassword")
     */
    public function changePasswordAction()
    {
        $peticion = Request::createFromGlobals();
        $idUsuario = $peticion->request->get('idUsuario');
        $passNew = $peticion->request->get('passNew');
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();

        $resp = $em->getRepository('AppBundle:Usuario')->cambiarPassword($idUsuario, $passNew);

        if(is_string($resp)) {
            return new Response($resp);
        }

        //se crea la traza
        $dataTraza = array(
            'modulo' => 'Usuario',
            'accion' => 'Resetear',
            'descripcion' => 'Se cambió la contraseña del usuario: ' . $user->getNombreCompleto()
        );
        $traza = $em->getRepository('AppBundle:Traza')->guardarTraza($user,$dataTraza);

        if (is_string($traza)) {
            return new Response($traza);
        }

        return new Response('ok');
    }

    //metodo para mostrar pantalla de bloqueo

    /**
     * @Route("/lock", name="lock")
     */
    public function lockAction()
    {
        return $this->render('Inicio/login.html.twig');
    }

    //metodo para desbloquear el sistema

    /**
     * @Route("/confirmPassword", name="confirmPassword")
     */
    public function confirmPasswordAction()
    {
        $peticion = Request::createFromGlobals();
        $password = $peticion->request->get('password');
        $user = $this->getUser();

        $encoder = $this->container->get('security.password_encoder');

        $valid = $encoder->isPasswordValid($user, $password);

        if ($valid === 1) {
            return new Response('ok');
        }

        return new Response('Error: Contraseña  errónea');
    }

}
