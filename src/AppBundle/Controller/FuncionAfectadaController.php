<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class FuncionAfectadaController extends Controller
{
    /**
     * @Route("/gestionarFuncionesAfectadas", name="gestionarFuncionesAfectadas")
     */
    public function gestionarFuncionesAfectadasAction()
    {
        $em = $this->getDoctrine()->getManager();
        $funcionesAfectadas  = $em->getRepository('AppBundle:FuncionAfectado')->findBy(array(),array('id' => 'ASC'));

        return $this->render('Nomencladores/FuncionesAfectadas/funcionesAfectadas.html.twig' , array(
            'funcionesAfectadas' => $funcionesAfectadas
        ));
    }

    /**
     * @Route("/insertFuncionAfectada", name="insertFuncionAfectada")
     */
    public function insertFuncionAfectadaAction()
    {
        $peticion = Request::createFromGlobals();
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();

        $data = array(
            'nombre' => $peticion->request->get('nombre')
        );

        $resp = $em->getRepository('AppBundle:FuncionAfectado')->agregarFuncionAfectado($data);

        if(is_string($resp)) {
            return new Response($resp);
        }

        //se crea la traza
        $dataTraza = array(
            'modulo' => 'Función Afectada',
            'accion' => 'Insertar',
            'descripcion' => 'Se insertó una nueva función afectada con el nombre: '.$data['nombre']
        );
        $traza = $em->getRepository('AppBundle:Traza')->guardarTraza($user,$dataTraza);

        if (is_string($traza)) {
            return new Response($traza);
        }

        return new Response('ok');
    }

    /**
     * @Route("/updateFuncionAfectada", name="updateFuncionAfectada")
     */
    public function updateFuncionAfectadaAction()
    {
        $peticion = Request::createFromGlobals();
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();

        $data = array(
            'id' => $peticion->request->get('id'),
            'nombre' => $peticion->request->get('nombre')
        );

        $resp = $em->getRepository('AppBundle:FuncionAfectado')->modificarFuncionAfectado($data);

        if(is_string($resp)) {
            return new Response($resp);
        }

        //se crea la traza
        $dataTraza = array(
            'modulo' => 'Función Afectada',
            'accion' => 'Modificar',
            'descripcion' => 'Se modificó la función afectada con el nombre: '.$data['nombre']
        );
        $traza = $em->getRepository('AppBundle:Traza')->guardarTraza($user,$dataTraza);

        if (is_string($traza)) {
            return new Response($traza);
        }

        return new Response('ok');
    }

    /**
     * @Route("/deleteFuncionAfectada", name="deleteFuncionAfectada")
     */
    public function deleteFuncionAfectadaAction()
    {
        $peticion = Request::createFromGlobals();
        $idFuncionAfectada = $peticion->request->get('id');
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();

        $resp  = $em->getRepository('AppBundle:FuncionAfectado')->eliminarFuncionAfectado($idFuncionAfectada);

        if(is_string($resp)) {
            return new Response($resp);
        }

        //se crea la traza
        $dataTraza = array(
            'modulo' => 'Función Afectada',
            'accion' => 'Eliminar',
            'descripcion' => 'Se eliminó la función afectada con el nombre: '.$resp->getNombre()
        );
        $traza = $em->getRepository('AppBundle:Traza')->guardarTraza($user,$dataTraza);

        if (is_string($traza)) {
            return new Response($traza);
        }

        return new Response('ok');
    }
}
