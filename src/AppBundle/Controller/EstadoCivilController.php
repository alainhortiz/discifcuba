<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class EstadoCivilController extends Controller
{
    /**
     * @Route("/gestionarEstadosCiviles", name="gestionarEstadosCiviles")
     */
    public function gestionarEstadosCivilesAction()
    {
        $em = $this->getDoctrine()->getManager();
        $estadosCiviles  = $em->getRepository('AppBundle:EstadoCivil')->findBy(array(),array('id' => 'ASC'));

        return $this->render('Nomencladores/EstadosCiviles/estadosCiviles.html.twig' , array(
            'estadosCiviles' => $estadosCiviles
        ));
    }

    /**
     * @Route("/insertEstadoCivil", name="insertEstadoCivil")
     */
    public function insertEstadoCivilAction()
    {
        $peticion = Request::createFromGlobals();
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();

        $data = array(
            'nombre' => $peticion->request->get('nombre')
        );

        $resp = $em->getRepository('AppBundle:EstadoCivil')->agregarEstadoCivil($data);

        if(is_string($resp)) {
            return new Response($resp);
        }

        //se crea la traza
        $dataTraza = array(
            'modulo' => 'Estado Civil',
            'accion' => 'Insertar',
            'descripcion' => 'Se insertó un nuevo estado civil con el nombre: '.$data['nombre']
        );
        $traza = $em->getRepository('AppBundle:Traza')->guardarTraza($user,$dataTraza);

        if (is_string($traza)) {
            return new Response($traza);
        }

        return new Response('ok');
    }

    /**
     * @Route("/updateEstadoCivil", name="updateEstadoCivil")
     */
    public function updateEstadoCivilAction()
    {
        $peticion = Request::createFromGlobals();
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();

        $data = array(
            'id' => $peticion->request->get('id'),
            'nombre' => $peticion->request->get('nombre')
        );

        $resp = $em->getRepository('AppBundle:EstadoCivil')->modificarEstadoCivil($data);

        if(is_string($resp)) {
            return new Response($resp);
        }

        //se crea la traza
        $dataTraza = array(
            'modulo' => 'Estado Civil',
            'accion' => 'Modificar',
            'descripcion' => 'Se modificó el estado civil con el nombre: '.$data['nombre']
        );
        $traza = $em->getRepository('AppBundle:Traza')->guardarTraza($user,$dataTraza);

        if (is_string($traza)) {
            return new Response($traza);
        }

        return new Response('ok');
    }

    /**
     * @Route("/deleteEstadoCivil", name="deleteEstadoCivil")
     */
    public function deleteEstadoCivilAction()
    {
        $peticion = Request::createFromGlobals();
        $idEstadoCivil = $peticion->request->get('id');
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();

        $resp  = $em->getRepository('AppBundle:EstadoCivil')->eliminarEstadoCivil($idEstadoCivil);

        if(is_string($resp)) {
            return new Response($resp);
        }

        //se crea la traza
        $dataTraza = array(
            'modulo' => 'Estado Civil',
            'accion' => 'Eliminar',
            'descripcion' => 'Se eliminó el estado civil con el nombre: '.$resp->getNombre()
        );
        $traza = $em->getRepository('AppBundle:Traza')->guardarTraza($user,$dataTraza);

        if (is_string($traza)) {
            return new Response($traza);
        }

        return new Response('ok');
    }
}
