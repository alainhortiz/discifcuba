<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ProfesionController extends Controller
{
    /**
     * @Route("/gestionarProfesiones", name="gestionarProfesiones")
     */
    public function gestionarProfesionesAction()
    {
        $em = $this->getDoctrine()->getManager();
        $profesiones  = $em->getRepository('AppBundle:Profesion')->findBy(array(),array('id' => 'ASC'));

        return $this->render('Nomencladores/Profesiones/profesiones.twig' , array(
            'profesiones' => $profesiones
        ));
    }

    /**
     * @Route("/insertProfesion", name="insertProfesion")
     */
    public function insertProfesionAction()
    {
        $peticion = Request::createFromGlobals();
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();

        $data = array(
            'nombre' => $peticion->request->get('nombre')
        );

        $resp = $em->getRepository('AppBundle:Profesion')->agregarProfesion($data);

        if(is_string($resp)) {
            return new Response($resp);
        }

        //se crea la traza
        $dataTraza = array(
            'modulo' => 'Profesión',
            'accion' => 'Insertar',
            'descripcion' => 'Se insertó un nueva profesión con el nombre: '.$data['nombre']
        );
        $traza = $em->getRepository('AppBundle:Traza')->guardarTraza($user,$dataTraza);

        if (is_string($traza)) {
            return new Response($traza);
        }

        return new Response('ok');
    }

    /**
     * @Route("/updateProfesion", name="updateProfesion")
     */
    public function updateProfesionAction()
    {
        $peticion = Request::createFromGlobals();
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();

        $data = array(
            'id' => $peticion->request->get('id'),
            'nombre' => $peticion->request->get('nombre')
        );

        $resp = $em->getRepository('AppBundle:Profesion')->modificarProfesion($data);

        if(is_string($resp)) {
            return new Response($resp);
        }

        //se crea la traza
        $dataTraza = array(
            'modulo' => 'Profesión',
            'accion' => 'Modificar',
            'descripcion' => 'Se modificó la profesión con el nombre: '.$data['nombre']
        );
        $traza = $em->getRepository('AppBundle:Traza')->guardarTraza($user,$dataTraza);

        if (is_string($traza)) {
            return new Response($traza);
        }

        return new Response('ok');
    }

    /**
     * @Route("/deleteProfesion", name="deleteProfesion")
     */
    public function deleteProfesionAction()
    {
        $peticion = Request::createFromGlobals();
        $idProfesion = $peticion->request->get('id');
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();

        $resp  = $em->getRepository('AppBundle:Profesion')->eliminarProfesion($idProfesion);

        if(is_string($resp)) {
            return new Response($resp);
        }

        //se crea la traza
        $dataTraza = array(
            'modulo' => 'Profesión',
            'accion' => 'Eliminar',
            'descripcion' => 'Se eliminó la profesión con el nombre: '.$resp->getNombre()
        );
        $traza = $em->getRepository('AppBundle:Traza')->guardarTraza($user,$dataTraza);

        if (is_string($traza)) {
            return new Response($traza);
        }

        return new Response('ok');
    }

}
