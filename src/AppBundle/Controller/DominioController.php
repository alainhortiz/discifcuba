<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class DominioController extends Controller
{
    /**
     * @Route("/gestionarPreguntas", name="gestionarPreguntas")
     */
    public function gestionarPreguntasAction()
    {
        $em = $this->getDoctrine()->getManager();
        $dominios = $em->getRepository('AppBundle:TipoDominio')->findAll();
        $preguntas = $em->getRepository('AppBundle:Pregunta')->findAll();

        return $this->render('Nomencladores/Preguntas/preguntas.html.twig', array(
            'dominios' => $dominios,
            'preguntas' => $preguntas
        ));
    }

    /**
     * @Route("/dataTablePreguntasAjax", name="dataTablePreguntasAjax")
     */
    public function dataTablePreguntasAjaxAction()
    {
        $entityManager = $this->getDoctrine()->getManager();

        //DataSource del DataTable
        $dql = "SELECT p.id, d.codigo as codigoDominio, d.nombre as dominio, p.codigo as codigoPregunta, p.nombre as pregunta FROM AppBundle:Pregunta p INNER JOIN p.tipoDominio d ORDER BY d.codigo, p.codigo";
        $dqlTotalRecords = "SELECT count(p) FROM AppBundle:Pregunta p INNER JOIN p.tipoDominio d";
        $dqlCountFiltered = "SELECT count(p) FROM AppBundle:Pregunta p INNER JOIN p.tipoDominio d";

        //Esto es para armar el filtro que viene del front
        $sqlFilter = "";

        if (!empty($_GET['search']['value'])) {
            $strMainSearch = $_GET['search']['value'];

            $sqlFilter .= " (p.id  LIKE '%".$strMainSearch."%' OR "
                ."d.codigo  LIKE '%".$strMainSearch."%' OR "
                ."d.nombre  LIKE '%".$strMainSearch."%' OR "
                ."p.codigo  LIKE '%".$strMainSearch."%' OR "
                ."p.nombre LIKE '%".$strMainSearch."%') ";
        }

        //Armar el buscador de los footer
        // Filter columns with AND restriction
        $strColSearch = "";
        foreach ($_GET['columns'] as $column) {
            if (!empty($column['search']['value'])) {
                if (!empty($strColSearch)) {
                    $strColSearch .= ' AND ';
                }
                /*$strColSearch .= ' t.'.$column['name']." LIKE '%".$column['search']['value']."%'";*/
                if (!empty($column['search']['value']))
                {
                    switch ($column['name'])
                    {
                        case 'id': $strColSearch .= " p.id LIKE '%".$column['search']['value']."%' ";
                            break;
                        case 'codigoDominio': $strColSearch .= " d.codigo LIKE '%".$column['search']['value']."%' ";
                            break;
                        case 'dominio': $strColSearch .= " d.nombre LIKE '%".$column['search']['value']."%' ";
                            break;
                        case 'codigoPregunta':  $strColSearch .= " p.codigo LIKE '%".$column['search']['value']."%' ";
                            break;
                        case 'pregunta':  $strColSearch .= " p.nombre LIKE '%".$column['search']['value']."%' ";
                            break;
                    }
                }
            }
        }

        if (!empty($sqlFilter) and !empty($strColSearch)) {
            $sqlFilter .= ' AND ('.$strColSearch.')';
        } else {
            $sqlFilter .= $strColSearch;
        }

        if (!empty($sqlFilter)) {
            if(strpos($dql , 'WHERE'))
            {
                $dql .= ' AND'.$sqlFilter;
                $dqlCountFiltered .= ' AND'.$sqlFilter;
            }else{
                $dql .= ' WHERE'.$sqlFilter;
                $dqlCountFiltered .= ' WHERE'.$sqlFilter;
            }
        }

        //Ejecucion del dql
        $items = $entityManager
            ->createQuery($dql)
            ->setFirstResult($_GET['start'])
            ->setMaxResults($_GET['length'])
            ->getResult();

        //Armar el arreglo data con el resultado del dql
        $data = array();
        foreach ($items as $key => $value) {
            $data[] = array(
                $value['id'],
                $value['codigoDominio'],
                $value['dominio'],
                $value['codigoPregunta'],
                $value['pregunta'],
                $acciones = "<button class=\"btn btn-sm btn-info btn-icon-only rounded-circle editPregunta\"
                                                    type=\"button\" data-toggle=\"tooltip\"
                                                    data-placement=\"top\"
                                                    title=\"Modificar\">
                                        <span class=\"btn-inner--icon\"><i
                                                    class=\"fas fa-edit\"></i></span>
                                            </button>"
                    . "<button class=\"btn btn-sm btn-danger btn-icon-only rounded-circle borrarPregunta\"
                                                    type=\"button\" data-toggle=\"tooltip\"
                                                    data-placement=\"top\"
                                                    title=\"Eliminar\">
                                                                                 <span class=\"btn-inner--icon\"><i
                                                                                             class=\"fas fa-trash\"></i></span>
                                            </button>",
            );
        }

        $recordsTotal = $entityManager
            ->createQuery($dqlTotalRecords)
            ->getSingleScalarResult();

        $recordsFiltered = $entityManager
            ->createQuery($dqlCountFiltered)
            ->getSingleScalarResult();

        $output = array(
            'draw' => 0,
            'recordsTotal' => $recordsTotal,
            'recordsFiltered' => $recordsFiltered,
            'data' => $data,
            'dql' => $dql,
            'dqlCountFiltered' => $dqlCountFiltered,
        );

        return new JsonResponse($output);
    }

    /**
     * @Route("/insertPregunta", name="insertPregunta")
     */
    public function insertPreguntaAction()
    {
        $peticion = Request::createFromGlobals();
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();

        $data = array(
            'dominio' => $peticion->request->get('dominio'),
            'codigo' => $peticion->request->get('codigo'),
            'nombre' => $peticion->request->get('nombre')
        );

        $pregunta = $em->getRepository('AppBundle:Pregunta')->agregarPregunta($data);

        if (is_string($pregunta)) {
            return new Response($pregunta);
        }

        //se crea la traza
        $dataTraza = array(
            'modulo' => 'Pregunta',
            'accion' => 'Insertar',
            'descripcion' => 'Se insertó una nueva pregunta con el código: ' . $data['codigo']
        );
        $traza = $em->getRepository('AppBundle:Traza')->guardarTraza($user,$dataTraza);

        if (is_string($traza)) {
            return new Response($traza);
        }

        return new Response('ok');
    }

    /**
     * @Route("/updatePregunta", name="updatePregunta")
     */
    public function updatePreguntaAction()
    {
        $peticion = Request::createFromGlobals();
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();

        $data = array(
            'idPregunta' => $peticion->request->get('idPregunta'),
            'codigo' => $peticion->request->get('codigo'),
            'nombre' => $peticion->request->get('nombre'),
            'dominio' => $peticion->request->get('dominio')
        );

        $pregunta = $em->getRepository('AppBundle:Pregunta')->modificarPregunta($data);

        if (is_string($pregunta)) {
            return new Response($pregunta);
        }

        //se crea la traza
        $dataTraza = array(
            'modulo' => 'Pregunta',
            'accion' => 'Modificar',
            'descripcion' => 'Se modificó la pregunta con el código: ' . $data['codigo']
        );
        $traza = $em->getRepository('AppBundle:Traza')->guardarTraza($user,$dataTraza);

        if (is_string($traza)) {
            return new Response($traza);
        }

        return new Response('ok');

    }

    /**
     * @Route("/deletePregunta", name="deletePregunta")
     */
    public function deletePreguntaAction()
    {
        $peticion = Request::createFromGlobals();
        $idPregunta = $peticion->request->get('idPregunta');
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();

        $pregunta = $em->getRepository('AppBundle:Pregunta')->eliminarPregunta($idPregunta);

        if (is_string($pregunta)) {
            return new Response($pregunta);
        }

        //se crea la traza
        $dataTraza = array(
            'modulo' => 'Pregunta',
            'accion' => 'Eliminar',
            'descripcion' => 'Se eliminó la pregunta con el código: ' . $pregunta->getCodigo()
        );
        $traza = $em->getRepository('AppBundle:Traza')->guardarTraza($user,$dataTraza);

        if (is_string($traza)) {
            return new Response($traza);
        }

        return new Response('ok');
    }

}
