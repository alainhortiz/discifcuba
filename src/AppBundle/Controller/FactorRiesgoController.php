<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class FactorRiesgoController extends Controller
{
    /**
     * @Route("/gestionarFactoresRiesgos", name="gestionarFactoresRiesgos")
     */
    public function gestionarFactoresRiesgosAction()
    {
        $em = $this->getDoctrine()->getManager();
        $factoresRiesgos  = $em->getRepository('AppBundle:FactorRiesgo')->findBy(array(),array('id' => 'ASC'));

        return $this->render('Nomencladores/FactoresRiesgos/factoresRiesgos.html.twig' , array(
            'factoresRiesgos' => $factoresRiesgos
        ));
    }

    /**
     * @Route("/insertFactorRiesgo", name="insertFactorRiesgo")
     */
    public function insertFactorRiesgoAction()
    {
        $peticion = Request::createFromGlobals();
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();

        $data = array(
            'nombre' => $peticion->request->get('nombre')
        );

        $resp = $em->getRepository('AppBundle:FactorRiesgo')->agregarFactorRiesgo($data);

        if(is_string($resp)) {
            return new Response($resp);
        }

        //se crea la traza
        $dataTraza = array(
            'modulo' => 'Factor de Riesgo',
            'accion' => 'Insertar',
            'descripcion' => 'Se insertó un nuevo factor de riesgo con el nombre: '.$data['nombre']
        );
        $traza = $em->getRepository('AppBundle:Traza')->guardarTraza($user,$dataTraza);

        if (is_string($traza)) {
            return new Response($traza);
        }

        return new Response('ok');
    }

    /**
     * @Route("/updateFactorRiesgo", name="updateFactorRiesgo")
     */
    public function updateFactorRiesgoAction()
    {
        $peticion = Request::createFromGlobals();
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();

        $data = array(
            'id' => $peticion->request->get('id'),
            'nombre' => $peticion->request->get('nombre')
        );

        $resp = $em->getRepository('AppBundle:FactorRiesgo')->modificarFactorRiesgo($data);

        if(is_string($resp)) {
            return new Response($resp);
        }

        //se crea la traza
        $dataTraza = array(
            'modulo' => 'Factor de Riesgo',
            'accion' => 'Modificar',
            'descripcion' => 'Se modificó el factor de riesgo con el nombre: '.$data['nombre']
        );
        $traza = $em->getRepository('AppBundle:Traza')->guardarTraza($user,$dataTraza);

        if (is_string($traza)) {
            return new Response($traza);
        }

        return new Response('ok');
    }

    /**
     * @Route("/deleteFactorRiesgo", name="deleteFactorRiesgo")
     */
    public function deleteFactorRiesgoAction()
    {
        $peticion = Request::createFromGlobals();
        $idFactorRiesgo = $peticion->request->get('id');
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();

        $resp  = $em->getRepository('AppBundle:FactorRiesgo')->eliminarFactorRiesgo($idFactorRiesgo);

        if(is_string($resp)) {
            return new Response($resp);
        }

        //se crea la traza
        $dataTraza = array(
            'modulo' => 'Factor de Riesgo',
            'accion' => 'Eliminar',
            'descripcion' => 'Se eliminó el factor de riesgo con el nombre: '.$resp->getNombre()
        );
        $traza = $em->getRepository('AppBundle:Traza')->guardarTraza($user,$dataTraza);

        if (is_string($traza)) {
            return new Response($traza);
        }

        return new Response('ok');
    }
}
