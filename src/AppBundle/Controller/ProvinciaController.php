<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ProvinciaController extends Controller
{
    /**
     * @Route("/gestionarProvincias", name="gestionarProvincias")
     */
    public function gestionarProvinciasAction()
    {
        $em = $this->getDoctrine()->getManager();
        $provincias = $em->getRepository('AppBundle:Provincia')->findAll();

        return $this->render('Nomencladores/Provincias/provincias.html.twig', array(
            'provincias' => $provincias
        ));
    }

    /**
     * @Route("/insertProvincia", name="insertProvincia")
     */
    public function insertProvinciaAction()
    {
        $peticion = Request::createFromGlobals();
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();

        $data = array(
            'codigo' => $peticion->request->get('codigo'),
            'nombre' => $peticion->request->get('nombre')
        );

        $provincia = $em->getRepository('AppBundle:Provincia')->agregarProvincia($data);

        if (is_string($provincia)) {
            return new Response($provincia);
        }

        //se crea la traza
        $dataTraza = array(
            'modulo' => 'Provincia',
            'accion' => 'Insertar',
            'descripcion' => 'Se insertó una nueva provincia con el nombre: ' . $data['nombre']
        );
        $traza = $em->getRepository('AppBundle:Traza')->guardarTraza($user,$dataTraza);

        if (is_string($traza)) {
            return new Response($traza);
        }

        return new Response('ok');
    }

    /**
     * @Route("/updateProvincia", name="updateProvincia")
     */
    public function updateProvinciaAction()
    {
        $peticion = Request::createFromGlobals();
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();

        $data = array(
            'id' => $peticion->request->get('id'),
            'nombre' => $peticion->request->get('nombre'),
            'codigo' => $peticion->request->get('codigo')
        );

        $provincia = $em->getRepository('AppBundle:Provincia')->modificarProvincia($data);

        if (is_string($provincia)) {
            return new Response($provincia);
        }

        //se crea la traza
        $dataTraza = array(
            'modulo' => 'Provincia',
            'accion' => 'Modificar',
            'descripcion' => 'Se modificó la provincia con el nombre: ' . $data['nombre']
        );
        $traza = $em->getRepository('AppBundle:Traza')->guardarTraza($user,$dataTraza);

        if (is_string($traza)) {
            return new Response($traza);
        }

        return new Response('ok');

    }

    /**
     * @Route("/deleteProvincia", name="deleteProvincia")
     */
    public function deleteProvinciaAction()
    {
        $peticion = Request::createFromGlobals();
        $id = $peticion->request->get('id');
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();

        $provincia = $em->getRepository('AppBundle:Provincia')->eliminarProvincia($id);

        if (is_string($provincia)) {
            return new Response($provincia);
        }

        //se crea la traza
        $dataTraza = array(
            'modulo' => 'Provincia',
            'accion' => 'Eliminar',
            'descripcion' => 'Se eliminó la provincia con el nombre: ' . $provincia->getNombre()
        );
        $traza = $em->getRepository('AppBundle:Traza')->guardarTraza($user,$dataTraza);

        if (is_string($traza)) {
            return new Response($traza);
        }

        return new Response('ok');
    }
}
