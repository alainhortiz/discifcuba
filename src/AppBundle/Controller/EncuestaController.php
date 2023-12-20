<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class EncuestaController extends Controller
{
    /**
     * @Route("/gestionarEncuestas", name="gestionarEncuestas")
     */
    public function gestionarEncuestasAction()
    {
        $em = $this->getDoctrine()->getManager();
        $encuestas = $em->getRepository('AppBundle:Encuesta')->findAll();

        return $this->render('Encuestas/encuestas.html.twig', array(
            'encuestas' => $encuestas
        ));
    }

    /**
     * @Route("/dataTableEncuestasAjax", name="dataTableEncuestasAjax")
     */
    public function dataTableEncuestasAjaxAction()
    {
        $entityManager = $this->getDoctrine()->getManager();

        //DataSource del DataTable
        $dql = "SELECT a.id, p.nombre as provincia, m.nombre as municipio, a.fechaValoracion as fecha, pe.carnetIdentidad as carne, pe.nombreCompleto, u.nombreCompleto as entrevistador, a.aprobacion FROM AppBundle:Encuesta a INNER JOIN a.persona pe INNER JOIN a.muncipioResidencia m INNER JOIN m.provincia p INNER JOIN a.usuarioRegistra u";
        $dqlTotalRecords = "SELECT count(a) FROM AppBundle:Encuesta a INNER JOIN a.persona pe INNER JOIN a.muncipioResidencia m INNER JOIN m.provincia p INNER JOIN a.usuarioRegistra u";
        $dqlCountFiltered = "SELECT count(a) FROM AppBundle:Encuesta a INNER JOIN a.persona pe INNER JOIN a.muncipioResidencia m INNER JOIN m.provincia p INNER JOIN a.usuarioRegistra u";

        //Esto es para armar el filtro que viene del front
        $sqlFilter = "";

        if (!empty($_GET['search']['value'])) {
            $strMainSearch = $_GET['search']['value'];

            $sqlFilter .= " (a.id  LIKE '%" . $strMainSearch . "%' OR "
                . "p.nombre  LIKE '%" . $strMainSearch . "%' OR "
                . "m.nombre  LIKE '%" . $strMainSearch . "%' OR "
                . "a.fechaValoracion  LIKE '%" . $strMainSearch . "%' OR "
                . "pe.carnetIdentidad  LIKE '%" . $strMainSearch . "%' OR "
                . "pe.nombreCompleto  LIKE '%" . $strMainSearch . "%' OR "
                . "u.nombreCompleto  LIKE '%" . $strMainSearch . "%' OR "
                . "a.aprobacion LIKE '%" . $strMainSearch . "%') ";
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
                if (!empty($column['search']['value'])) {
                    switch ($column['name']) {
                        case 'id':
                            $strColSearch .= " a.id LIKE '%" . $column['search']['value'] . "%' ";
                            break;
                        case 'provincia':
                            $strColSearch .= " p.nombre LIKE '%" . $column['search']['value'] . "%' ";
                            break;
                        case 'municipio':
                            $strColSearch .= " m.nombre LIKE '%" . $column['search']['value'] . "%' ";
                            break;
                        case 'fecha':
                            $strColSearch .= " a.fechaValoracion LIKE '%" . $column['search']['value'] . "%' ";
                            break;
                        case 'carne':
                            $strColSearch .= " pe.carnetIdentidad LIKE '%" . $column['search']['value'] . "%' ";
                            break;
                        case 'nombreCompleto':
                            $strColSearch .= " pe.nombreCompleto LIKE '%" . $column['search']['value'] . "%' ";
                            break;
                        case 'entrevistador':
                            $strColSearch .= " u.nombreCompleto LIKE '%" . $column['search']['value'] . "%' ";
                            break;
                        case 'aprobacion':
                            $strColSearch .= " a.aprobacion LIKE '%" . $this->obtenerCodigoSupervisor($column['search']['value']) . "%' ";
                            break;
                    }
                }
            }
        }

        if (!empty($sqlFilter) and !empty($strColSearch)) {
            $sqlFilter .= ' AND (' . $strColSearch . ')';
        } else {
            $sqlFilter .= $strColSearch;
        }

        if (!empty($sqlFilter)) {
            if (strpos($dql, 'WHERE')) {
                $dql .= ' AND' . $sqlFilter;
                $dqlCountFiltered .= ' AND' . $sqlFilter;
            } else {
                $dql .= ' WHERE' . $sqlFilter;
                $dqlCountFiltered .= ' WHERE' . $sqlFilter;
            }
        }

        //Ejecucion del dql
        $items = $entityManager
            ->createQuery($dql)
            ->setFirstResult($_GET['start'])
            ->setMaxResults($_GET['length'])
            ->getResult();


        //Armar las acciones de los botones según el nivel de acceso
//        $botones = $this->botonesNiveles();

        //Armar el arreglo data con el resultado del dql
        $data = array();
        foreach ($items as $key => $value) {
            $data[] = array(
                $value['id'],
                $value['provincia'],
                $value['municipio'],
                $value['fecha']->format('Y-m-d'),
                $value['carne'],
                $value['nombreCompleto'],
                $value['entrevistador'],
                $this->obtenerNombreSupervisor($value['aprobacion']),
                $acciones = $this->botonesNiveles($value['id'], $value['entrevistador'], $value['aprobacion'])
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

    private function  obtenerCodigoSupervisor($aprobacion){
        $supervisor = "";
        switch ($aprobacion) {
            case 'Entrevistador':
                $supervisor = "1";
                break;
            case 'Supervisor policlínico':
                $supervisor = "2";
                break;
            case 'Supervisor municipal':
                $supervisor = "3";
                break;
            case 'Supervisor provincial':
                $supervisor = "4";
                break;
            case 'Supervisor nacional':
                $supervisor = "5";
                break;
        }
        return $supervisor;
    }

    private function  obtenerNombreSupervisor($aprobacion){
        $supervisor = "";
        switch ($aprobacion) {
            case '1':
                $supervisor = "Entrevistador";
                break;
            case '2':
                $supervisor = "Supervisor policlínico";
                break;
            case '3':
                $supervisor = "Supervisor municipal";
                break;
            case '4':
                $supervisor = "Supervisor provincial";
                break;
            case '5':
                $supervisor = "Supervisor nacional";
                break;
        }
        return $supervisor;
    }

    private function botonesNiveles($encuesta, $entrevistador, $aprobacion)
    {

        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();
        $supervisor = $user->getSupervisor();
        $nivelAccesoID = $user->getNivelAcceso()->getId();

        $result = "";
        $result .= "<button class=\"btn btn-sm btn-success btn-icon-only rounded-circle verEncuesta\"
                                                    type=\"button\" data-toggle=\"tooltip\"
                                                    data-placement=\"top\"
                                                    title=\"Ver\">
                                                                                 <span class=\"btn-inner--icon\"><i
                                                                                             class=\"fas fa-eye\"></i></span>
                                            </button>";

        //pregunto si es supervisor
        if ($supervisor) {
            //pregunto si el supervisor es de unidad y si la encuesta esta lista para aprobar a ese nivel y sucesivamente
            if($nivelAccesoID === 1 && $aprobacion === 1) {
                $result .= "<button class=\"btn btn-sm btn-default btn-icon-only rounded-circle aprobarEncuesta\"
                                                    type=\"button\" data-toggle=\"tooltip\"
                                                    data-placement=\"top\"
                                                    title=\"Aprobar\">
                                                                                 <span class=\"btn-inner--icon\"><i
                                                                                             class=\"fas fa-check\"></i></span>
                                            </button>";
            }elseif ($nivelAccesoID === 2 && $aprobacion === 2){
                $result .= "<button class=\"btn btn-sm btn-default btn-icon-only rounded-circle aprobarEncuesta\"
                                                    type=\"button\" data-toggle=\"tooltip\"
                                                    data-placement=\"top\"
                                                    title=\"Aprobar\">
                                                                                 <span class=\"btn-inner--icon\"><i
                                                                                             class=\"fas fa-check\"></i></span>
                                            </button>";
            }elseif ($nivelAccesoID === 3 && $aprobacion === 3){
                $result .= "<button class=\"btn btn-sm btn-default btn-icon-only rounded-circle aprobarEncuesta\"
                                                    type=\"button\" data-toggle=\"tooltip\"
                                                    data-placement=\"top\"
                                                    title=\"Aprobar\">
                                                                                 <span class=\"btn-inner--icon\"><i
                                                                                             class=\"fas fa-check\"></i></span>
                                            </button>";
            }elseif ($nivelAccesoID === 4 && $aprobacion === 4){
                $result .= "<button class=\"btn btn-sm btn-default btn-icon-only rounded-circle aprobarEncuesta\"
                                                    type=\"button\" data-toggle=\"tooltip\"
                                                    data-placement=\"top\"
                                                    title=\"Aprobar\">
                                                                                 <span class=\"btn-inner--icon\"><i
                                                                                             class=\"fas fa-check\"></i></span>
                                            </button>";
            }
        }

        //pregunto si no esta aprobada nacionalmente, de estarlo no se modifica
        if ($aprobacion !== 5){
            if ($entrevistador !== $user->getNombreCompleto()) {
                $verificar = $em->getRepository('AppBundle:Encuesta')->comprobarEncuesta($encuesta, $user->getId());
                if ($verificar > 0) {
                    $result .= "<button class=\"btn btn-sm btn-info btn-icon-only rounded-circle editEncuesta\"
                                                    type=\"button\" data-toggle=\"tooltip\"
                                                    data-placement=\"top\"
                                                    title=\"Modificar\">
                                                                                 <span class=\"btn-inner--icon\"><i
                                                                                             class=\"fas fa-edit\"></i></span>
                                            </button>"
                        . "<button class=\"btn btn-sm btn-danger btn-icon-only rounded-circle borrarEncuesta\"
                                                    type=\"button\" data-toggle=\"tooltip\"
                                                    data-placement=\"top\"
                                                    title=\"Eliminar\">
                                                                                 <span class=\"btn-inner--icon\"><i
                                                                                             class=\"fas fa-trash\"></i></span>
                                            </button>";
                }
            } else {
                $result .= "<button class=\"btn btn-sm btn-info btn-icon-only rounded-circle editEncuesta\"
                                                    type=\"button\" data-toggle=\"tooltip\"
                                                    data-placement=\"top\"
                                                    title=\"Modificar\">
                                                                                 <span class=\"btn-inner--icon\"><i
                                                                                             class=\"fas fa-edit\"></i></span>
                                            </button>"
                    . "<button class=\"btn btn-sm btn-danger btn-icon-only rounded-circle borrarEncuesta\"
                                                    type=\"button\" data-toggle=\"tooltip\"
                                                    data-placement=\"top\"
                                                    title=\"Eliminar\">
                                                                                 <span class=\"btn-inner--icon\"><i
                                                                                             class=\"fas fa-trash\"></i></span>
                                            </button>";
            }

        }

        return $result;
    }

    /**
     * @Route("/localizarPersona/{ci}", name="localizarPersona")
     */
    public function localizarPersonaAction($ci)
    {
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();
        $municipios = $em->getRepository('AppBundle:Municipio')->findAll();
        $provincias = $em->getRepository('AppBundle:Provincia')->findAll();
        $policlinicos = $em->getRepository('AppBundle:Policlinico')->findAll();
        $estadosCiviles = $em->getRepository('AppBundle:EstadoCivil')->findAll();
        $empleos = $em->getRepository('AppBundle:Empleo')->findAll();
        $grados = $em->getRepository('AppBundle:GradoIndependencia')->findAll();
        $factores = $em->getRepository('AppBundle:FactorRiesgo')->findAll();
        $diagnosticos = $em->getRepository('AppBundle:DiagnosticoMedico')->findAll();
        $sistemas = $em->getRepository('AppBundle:SistemaAfectado')->findAll();
        $funciones = $em->getRepository('AppBundle:FuncionAfectado')->findAll();
        $preguntas = $em->getRepository('AppBundle:Pregunta')->findAll();
        $entrevistadores = $em->getRepository('AppBundle:Usuario')->listarEntrevistadores();

        $persona = $em->getRepository('AppBundle:Persona')->findOneBy(array('carnetIdentidad' => $ci));
        if (!empty($persona)) {
            $encuesta = $em->getRepository('AppBundle:Encuesta')->findOneBy(array('persona' => $persona));
            if (!empty($encuesta)) {
                //Si no esta aprobada nacionalmente verfico si es algun entrevistador de la encuesta
                if (($encuesta->getAprobacion() < 5) && $encuesta->getUsuarioRegistra()->getId() === $user->getId()) {
                    return $this->render('Encuestas/editEncuesta.html.twig', array(
                        'persona' => $persona,
                        'encuesta' => $encuesta,
                        'municipios' => $municipios,
                        'provincias' => $provincias,
                        'policlinicos' => $policlinicos,
                        'estadosCiviles' => $estadosCiviles,
                        'empleos' => $empleos,
                        'grados' => $grados,
                        'factores' => $factores,
                        'diagnosticos' => $diagnosticos,
                        'sistemas' => $sistemas,
                        'funciones' => $funciones,
                        'entrevistadores' => $entrevistadores,
                        'preguntas' => $preguntas
                    ));
                }

                return $this->render('Encuestas/verEncuesta.html.twig', array(
                    'persona' => $persona,
                    'encuesta' => $encuesta
                ));
            }

            return $this->render('Encuestas/addEncuesta.html.twig', array(
                'ci' => $ci,
                'persona' => $persona,
                'municipios' => $municipios,
                'provincias' => $provincias,
                'policlinicos' => $policlinicos,
                'estadosCiviles' => $estadosCiviles,
                'empleos' => $empleos,
                'grados' => $grados,
                'factores' => $factores,
                'diagnosticos' => $diagnosticos,
                'sistemas' => $sistemas,
                'funciones' => $funciones,
                'entrevistadores' => $entrevistadores,
                'preguntas' => $preguntas
            ));
        }

        return $this->render('Encuestas/addEncuesta.html.twig', array(
            'ci' => $ci,
            'persona' => $persona,
            'municipios' => $municipios,
            'provincias' => $provincias,
            'policlinicos' => $policlinicos,
            'estadosCiviles' => $estadosCiviles,
            'empleos' => $empleos,
            'grados' => $grados,
            'factores' => $factores,
            'diagnosticos' => $diagnosticos,
            'sistemas' => $sistemas,
            'funciones' => $funciones,
            'entrevistadores' => $entrevistadores,
            'preguntas' => $preguntas
        ));

    }

    /**
     * @Route("/insertEncuesta", name="insertEncuesta")
     */
    public function insertEncuestaAction()
    {
        $peticion = Request::createFromGlobals();
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();

        $dataPersona = array(
            'ci' => $peticion->request->get('ci'),
            'nombre' => $peticion->request->get('nombre'),
            'primerApellido' => $peticion->request->get('primerApellido'),
            'segundoApellido' => $peticion->request->get('segundoApellido'),
            'fechaNacimiento' => $peticion->request->get('fechaNacimiento'),
            'sexo' => $peticion->request->get('sexo'),
        );

        $dataGenerales = array(
            'fechaValoracion' => $peticion->request->get('fechaValoracion'),
            'policlinico' => $peticion->request->get('policlinico'),
            'municipioResidencia' => $peticion->request->get('municipioResidencia'),
            'edad' => $peticion->request->get('edad'),
            'genero' => $peticion->request->get('genero'),
            'estadoCivil' => $peticion->request->get('estadoCivil'),
            'empleos' => $peticion->request->get('empleos'),
            'entrevistadores' => $peticion->request->get('entrevistadores'),
        );

        $dataDiagnostico = array(
            'grados' => $peticion->request->get('grados'),
            'factores' => $peticion->request->get('factores'),
            'diagnosticos' => $peticion->request->get('diagnosticos'),
            'sistemas' => $peticion->request->get('sistemas'),
            'funciones' => $peticion->request->get('funciones'),
        );

        $dataPreguntas = array(
            'd1' => $peticion->request->get('d1'),
            'd2' => $peticion->request->get('d2'),
            'd3' => $peticion->request->get('d3'),
            'd4' => $peticion->request->get('d4'),
            'd5' => $peticion->request->get('d5'),
            'd6' => $peticion->request->get('d6'),
            'd7' => $peticion->request->get('d7'),
            'd8' => $peticion->request->get('d8'),
            'd9' => $peticion->request->get('d9'),
        );

        $resp = $em->getRepository('AppBundle:Encuesta')->masterAgregarEncuesta($user, $dataPersona, $dataGenerales, $dataDiagnostico, $dataPreguntas);

        if (is_string($resp)) {
            return new Response($resp);
        }

        return new Response('ok');
    }

    /**
     * @Route("/verEncuesta/{idEncuesta}", name="verEncuesta")
     */
    public function verEncuestaAction($idEncuesta)
    {
        $em = $this->getDoctrine()->getManager();
        $encuesta = $em->getRepository('AppBundle:Encuesta')->find($idEncuesta);

        return $this->render('Encuestas/verEncuesta.html.twig', array(
            'encuesta' => $encuesta
        ));
    }

    /**
     * @Route("/editEncuesta/{idEncuesta}", name="editEncuesta")
     */
    public function editEncuestaAction($idEncuesta)
    {
        $em = $this->getDoctrine()->getManager();
        $encuesta = $em->getRepository('AppBundle:Encuesta')->find($idEncuesta);
        $municipios = $em->getRepository('AppBundle:Municipio')->findAll();
        $provincias = $em->getRepository('AppBundle:Provincia')->findAll();
        $policlinicos = $em->getRepository('AppBundle:Policlinico')->findAll();
        $estadosCiviles = $em->getRepository('AppBundle:EstadoCivil')->findAll();
        $empleos = $em->getRepository('AppBundle:Empleo')->findAll();
        $grados = $em->getRepository('AppBundle:GradoIndependencia')->findAll();
        $factores = $em->getRepository('AppBundle:FactorRiesgo')->findAll();
        $diagnosticos = $em->getRepository('AppBundle:DiagnosticoMedico')->findAll();
        $sistemas = $em->getRepository('AppBundle:SistemaAfectado')->findAll();
        $funciones = $em->getRepository('AppBundle:FuncionAfectado')->findAll();
        $entrevistadores = $em->getRepository('AppBundle:Usuario')->listarEntrevistadores();

        return $this->render('Encuestas/editEncuesta.html.twig', array(
            'encuesta' => $encuesta,
            'municipios' => $municipios,
            'provincias' => $provincias,
            'policlinicos' => $policlinicos,
            'estadosCiviles' => $estadosCiviles,
            'empleos' => $empleos,
            'grados' => $grados,
            'factores' => $factores,
            'diagnosticos' => $diagnosticos,
            'sistemas' => $sistemas,
            'funciones' => $funciones,
            'entrevistadores' => $entrevistadores,
        ));
    }

    /**
     * @Route("/updateEncuesta", name="updateEncuesta")
     */
    public function updateEncuestaAction()
    {
        $peticion = Request::createFromGlobals();
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();

        $dataPersona = array(
            'nombre' => $peticion->request->get('nombre'),
            'primerApellido' => $peticion->request->get('primerApellido'),
            'segundoApellido' => $peticion->request->get('segundoApellido'),
            'fechaNacimiento' => $peticion->request->get('fechaNacimiento'),
            'sexo' => $peticion->request->get('sexo'),
        );

        $dataGenerales = array(
            'idPersona' => $peticion->request->get('idPersona'),
            'id' => $peticion->request->get('id'),
            'fechaValoracion' => $peticion->request->get('fechaValoracion'),
            'policlinico' => $peticion->request->get('policlinico'),
            'municipioResidencia' => $peticion->request->get('municipioResidencia'),
            'edad' => $peticion->request->get('edad'),
            'genero' => $peticion->request->get('genero'),
            'estadoCivil' => $peticion->request->get('estadoCivil'),
            'empleos' => $peticion->request->get('empleos'),
            'entrevistadores' => $peticion->request->get('entrevistadores'),
        );

        $dataDiagnostico = array(
            'grados' => $peticion->request->get('grados'),
            'factores' => $peticion->request->get('factores'),
            'diagnosticos' => $peticion->request->get('diagnosticos'),
            'sistemas' => $peticion->request->get('sistemas'),
            'funciones' => $peticion->request->get('funciones'),
        );

        $dataPreguntas = array(
            'd1' => $peticion->request->get('d1'),
            'd2' => $peticion->request->get('d2'),
            'd3' => $peticion->request->get('d3'),
            'd4' => $peticion->request->get('d4'),
            'd5' => $peticion->request->get('d5'),
            'd6' => $peticion->request->get('d6'),
            'd7' => $peticion->request->get('d7'),
            'd8' => $peticion->request->get('d8'),
            'd9' => $peticion->request->get('d9'),
        );

//        var_dump($dataGenerales);
//        exit();

        $resp = $em->getRepository('AppBundle:Encuesta')->masterModificarEncuesta($user, $dataPersona, $dataGenerales, $dataDiagnostico, $dataPreguntas);

        if (is_string($resp)) {
            return new Response($resp);
        }

        return new Response('ok');

    }

    /**
     * @Route("/deleteEncuesta", name="deleteEncuesta")
     */
    public function deleteEncuestaAction()
    {
        $peticion = Request::createFromGlobals();
        $id = $peticion->request->get('id');
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();

        $resp = $em->getRepository('AppBundle:Encuesta')->masterEliminarEncuesta($user, $id);

        if (is_string($resp)) {
            return new Response($resp);
        }

        return new Response('ok');
    }

    /**
     * @Route("/aprobarEncuesta", name="aprobarEncuesta")
     */
    public function aprobarEncuestaAction()
    {
        $peticion = Request::createFromGlobals();
        $id = $peticion->request->get('id');
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();

        $resp = $em->getRepository('AppBundle:Encuesta')->masterAprobarEncuesta($user, $id);

        if (is_string($resp)) {
            return new Response($resp);
        }

        return new Response('ok');
    }
}
