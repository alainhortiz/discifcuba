<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class DiagnosticoMedicoController extends Controller
{
    /**
     * @Route("/gestionarDiagnosticosMedicos", name="gestionarDiagnosticosMedicos")
     */
    public function gestionarDiagnosticosMedicosAction()
    {
        $em = $this->getDoctrine()->getManager();
        $diagnosticosMedicos  = $em->getRepository('AppBundle:DiagnosticoMedico')->findBy(array(),array('id' => 'ASC'));

        return $this->render('Nomencladores/DiagnosticosMedicos/diagnosticosMedicos.html.twig' , array(
            'diagnosticosMedicos' => $diagnosticosMedicos
        ));
    }

    /**
     * @Route("/insertDiagnosticoMedico", name="insertDiagnosticoMedico")
     */
    public function insertDiagnosticoMedicoAction()
    {
        $peticion = Request::createFromGlobals();
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();

        $data = array(
            'nombre' => $peticion->request->get('nombre')
        );

        $resp = $em->getRepository('AppBundle:DiagnosticoMedico')->agregarDiagnosticoMedico($data);

        if(is_string($resp)) {
            return new Response($resp);
        }

        //se crea la traza
        $dataTraza = array(
            'modulo' => 'Diagnóstico Médico',
            'accion' => 'Insertar',
            'descripcion' => 'Se insertó un nuevo diagnóstico médico con el nombre: '.$data['nombre']
        );
        $traza = $em->getRepository('AppBundle:Traza')->guardarTraza($user,$dataTraza);

        if (is_string($traza)) {
            return new Response($traza);
        }

        return new Response('ok');
    }

    /**
     * @Route("/updateDiagnosticoMedico", name="updateDiagnosticoMedico")
     */
    public function updateDiagnosticoMedicoAction()
    {
        $peticion = Request::createFromGlobals();
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();

        $data = array(
            'id' => $peticion->request->get('id'),
            'nombre' => $peticion->request->get('nombre')
        );

        $resp = $em->getRepository('AppBundle:DiagnosticoMedico')->modificarDiagnosticoMedico($data);


        if(is_string($resp)) {
            return new Response($resp);
        }

        //se crea la traza
        $dataTraza = array(
            'modulo' => 'Diagnóstico Médico',
            'accion' => 'Modificar',
            'descripcion' => 'Se modificó el diagnóstico médico con el nombre: '.$data['nombre']
        );
        $traza = $em->getRepository('AppBundle:Traza')->guardarTraza($user,$dataTraza);

        if (is_string($traza)) {
            return new Response($traza);
        }

        return new Response('ok');
    }

    /**
     * @Route("/deleteDiagnosticoMedico", name="deleteDiagnosticoMedico")
     */
    public function deleteDiagnosticoMedicoAction()
    {
        $peticion = Request::createFromGlobals();
        $idDiagnosticoMedico = $peticion->request->get('id');
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();

        $resp  = $em->getRepository('AppBundle:DiagnosticoMedico')->eliminarDiagnosticoMedico($idDiagnosticoMedico);

        if(is_string($resp)) {
            return new Response($resp);
        }

        //se crea la traza
        $dataTraza = array(
            'modulo' => 'Diagnóstico Médico',
            'accion' => 'Eliminar',
            'descripcion' => 'Se eliminó el diagnóstico médico con el nombre: '.$resp->getNombre()
        );
        $traza = $em->getRepository('AppBundle:Traza')->guardarTraza($user,$dataTraza);

        if (is_string($traza)) {
            return new Response($traza);
        }

        return new Response('ok');
    }
}
