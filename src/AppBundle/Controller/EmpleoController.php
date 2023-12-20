<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class EmpleoController extends Controller
{
    /**
     * @Route("/gestionarEmpleos", name="gestionarEmpleos")
     */
    public function gestionarEmpleosAction()
    {
        $em = $this->getDoctrine()->getManager();
        $empleos  = $em->getRepository('AppBundle:Empleo')->findBy(array(),array('id' => 'ASC'));

        return $this->render('Nomencladores/Empleos/empleos.html.twig' , array(
            'empleos' => $empleos
        ));
    }

    /**
     * @Route("/insertEmpleo", name="insertEmpleo")
     */
    public function insertEmpleoAction()
    {
        $peticion = Request::createFromGlobals();
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();

        $data = array(
            'nombre' => $peticion->request->get('nombre')
        );

        $resp = $em->getRepository('AppBundle:Empleo')->agregarEmpleo($data);

        if(is_string($resp)) {
            return new Response($resp);
        }

        //se crea la traza
        $dataTraza = array(
            'modulo' => 'Empleo',
            'accion' => 'Insertar',
            'descripcion' => 'Se insertó un nuevo empleo con el nombre: '.$data['nombre']
        );
        $traza = $em->getRepository('AppBundle:Traza')->guardarTraza($user,$dataTraza);

        if (is_string($traza)) {
            return new Response($traza);
        }

        return new Response('ok');
    }

    /**
     * @Route("/updateEmpleo", name="updateEmpleo")
     */
    public function updateEmpleoAction()
    {
        $peticion = Request::createFromGlobals();
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();

        $data = array(
            'id' => $peticion->request->get('id'),
            'nombre' => $peticion->request->get('nombre')
        );

        $resp = $em->getRepository('AppBundle:Empleo')->modificarEmpleo($data);

        if(is_string($resp)) {
            return new Response($resp);
        }

        //se crea la traza
        $dataTraza = array(
            'modulo' => 'Empleo',
            'accion' => 'Modificar',
            'descripcion' => 'Se modificó el empleo con el nombre: '.$data['nombre']
        );
        $traza = $em->getRepository('AppBundle:Traza')->guardarTraza($user,$dataTraza);

        if (is_string($traza)) {
            return new Response($traza);
        }

        return new Response('ok');
    }

    /**
     * @Route("/deleteEmpleo", name="deleteEmpleo")
     */
    public function deleteEmpleoAction()
    {
        $peticion = Request::createFromGlobals();
        $idEmpleo = $peticion->request->get('id');
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();

        $resp  = $em->getRepository('AppBundle:Empleo')->eliminarEmpleo($idEmpleo);

        if(is_string($resp)) {
            return new Response($resp);
        }

        //se crea la traza
        $dataTraza = array(
            'modulo' => 'Empleo',
            'accion' => 'Eliminar',
            'descripcion' => 'Se eliminó el empleo con el nombre: '.$resp->getNombre()
        );
        $traza = $em->getRepository('AppBundle:Traza')->guardarTraza($user,$dataTraza);

        if (is_string($traza)) {
            return new Response($traza);
        }

        return new Response('ok');
    }
}
