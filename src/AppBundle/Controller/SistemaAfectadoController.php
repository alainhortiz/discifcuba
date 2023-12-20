<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class SistemaAfectadoController extends Controller
{
    /**
     * @Route("/gestionarSistemasAfectados", name="gestionarSistemasAfectados")
     */
    public function gestionarSistemasAfectadosAction()
    {
        $em = $this->getDoctrine()->getManager();
        $sistemasAfectados  = $em->getRepository('AppBundle:SistemaAfectado')->findBy(array(),array('id' => 'ASC'));

        return $this->render('Nomencladores/SistemasAfectados/sistemasAfectados.html.twig' , array(
            'sistemasAfectados' => $sistemasAfectados
        ));
    }

    /**
     * @Route("/insertSistemaAfectado", name="insertSistemaAfectado")
     */
    public function insertSistemaAfectadoAction()
    {
        $peticion = Request::createFromGlobals();
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();

        $data = array(
            'nombre' => $peticion->request->get('nombre')
        );

        $resp = $em->getRepository('AppBundle:SistemaAfectado')->agregarSistemaAfectado($data);

        if(is_string($resp)) {
            return new Response($resp);
        }

        //se crea la traza
        $dataTraza = array(
            'modulo' => 'Sistema Afectado',
            'accion' => 'Insertar',
            'descripcion' => 'Se insertó un nuevo sistema afectado con el nombre: '.$data['nombre']
        );
        $traza = $em->getRepository('AppBundle:Traza')->guardarTraza($user,$dataTraza);

        if (is_string($traza)) {
            return new Response($traza);
        }

        return new Response('ok');
    }

    /**
     * @Route("/updateSistemaAfectado", name="updateSistemaAfectado")
     */
    public function updateSistemaAfectadoAction()
    {
        $peticion = Request::createFromGlobals();
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();

        $data = array(
            'id' => $peticion->request->get('id'),
            'nombre' => $peticion->request->get('nombre')
        );

        $resp = $em->getRepository('AppBundle:SistemaAfectado')->modificarSistemaAfectado($data);

        if(is_string($resp)) {
            return new Response($resp);
        }

        //se crea la traza
        $dataTraza = array(
            'modulo' => 'Sistema Afectado',
            'accion' => 'Modificar',
            'descripcion' => 'Se modificó el sistema afectado con el nombre: '.$data['nombre']
        );
        $traza = $em->getRepository('AppBundle:Traza')->guardarTraza($user,$dataTraza);

        if (is_string($traza)) {
            return new Response($traza);
        }

        return new Response('ok');
    }

    /**
     * @Route("/deleteSistemaAfectado", name="deleteSistemaAfectado")
     */
    public function deleteSistemaAfectadoAction()
    {
        $peticion = Request::createFromGlobals();
        $idSistemaAfectado = $peticion->request->get('id');
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();

        $resp  = $em->getRepository('AppBundle:SistemaAfectado')->eliminarSistemaAfectado($idSistemaAfectado);

        if(is_string($resp)) {
            return new Response($resp);
        }

        //se crea la traza
        $dataTraza = array(
            'modulo' => 'Sistema Afectado',
            'accion' => 'Eliminar',
            'descripcion' => 'Se eliminó el sistema afectado con el nombre: '.$resp->getNombre()
        );
        $traza = $em->getRepository('AppBundle:Traza')->guardarTraza($user,$dataTraza);

        if (is_string($traza)) {
            return new Response($traza);
        }

        return new Response('ok');
    }
}
