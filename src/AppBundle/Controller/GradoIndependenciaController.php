<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class GradoIndependenciaController extends Controller
{
    /**
     * @Route("/gestionarGradosIndependencias", name="gestionarGradosIndependencias")
     */
    public function gestionarGradosIndependenciasAction()
    {
        $em = $this->getDoctrine()->getManager();
        $gradosIndependencias  = $em->getRepository('AppBundle:GradoIndependencia')->findBy(array(),array('id' => 'ASC'));

        return $this->render('Nomencladores/GradosIndependencias/gradosIndependencias.html.twig' , array(
            'gradosIndependencias' => $gradosIndependencias
        ));
    }

    /**
     * @Route("/insertGradoIndependencia", name="insertGradoIndependencia")
     */
    public function insertGradoIndependenciaAction()
    {
        $peticion = Request::createFromGlobals();
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();

        $data = array(
            'nombre' => $peticion->request->get('nombre')
        );

        $resp = $em->getRepository('AppBundle:GradoIndependencia')->agregarGradoIndependencia($data);

        if(is_string($resp)) {
            return new Response($resp);
        }

        //se crea la traza
        $dataTraza = array(
            'modulo' => 'Grado Independencia',
            'accion' => 'Insertar',
            'descripcion' => 'Se insertó una nueva grado de independencia con el nombre: '.$data['nombre']
        );
        $traza = $em->getRepository('AppBundle:Traza')->guardarTraza($user,$dataTraza);

        if (is_string($traza)) {
            return new Response($traza);
        }

        return new Response('ok');
    }

    /**
     * @Route("/updateGradoIndependencia", name="updateGradoIndependencia")
     */
    public function updateGradoIndependenciaAction()
    {
        $peticion = Request::createFromGlobals();
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();

        $data = array(
            'id' => $peticion->request->get('id'),
            'nombre' => $peticion->request->get('nombre')
        );

        $resp = $em->getRepository('AppBundle:GradoIndependencia')->modificarGradoIndependencia($data);

        if(is_string($resp)) {
            return new Response($resp);
        }

        //se crea la traza
        $dataTraza = array(
            'modulo' => 'Grado Independencia',
            'accion' => 'Modificar',
            'descripcion' => 'Se modificó el grado de independencia con el nombre: '.$data['nombre']
        );
        $traza = $em->getRepository('AppBundle:Traza')->guardarTraza($user,$dataTraza);

        if (is_string($traza)) {
            return new Response($traza);
        }

        return new Response('ok');
    }

    /**
     * @Route("/deleteGradoIndependencia", name="deleteGradoIndependencia")
     */
    public function deleteGradoIndependenciaAction()
    {
        $peticion = Request::createFromGlobals();
        $idGradoIndependencia = $peticion->request->get('id');
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();

        $resp  = $em->getRepository('AppBundle:GradoIndependencia')->eliminarGradoIndependencia($idGradoIndependencia);

        if(is_string($resp)) {
            return new Response($resp);
        }

        //se crea la traza
        $dataTraza = array(
            'modulo' => 'Grado Independencia',
            'accion' => 'Eliminar',
            'descripcion' => 'Se eliminó el grado de independencia con el nombre: '.$resp->getNombre()
        );
        $traza = $em->getRepository('AppBundle:Traza')->guardarTraza($user,$dataTraza);

        if (is_string($traza)) {
            return new Response($traza);
        }

        return new Response('ok');
    }
}
