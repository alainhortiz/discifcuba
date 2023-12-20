<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class PoliclinicoController extends Controller
{
    /**
     * @Route("/gestionarPoliclinicos", name="gestionarPoliclinicos")
     */
    public function gestionarPoliclinicosAction()
    {
        $em = $this->getDoctrine()->getManager();
        $provincias = $em->getRepository('AppBundle:Provincia')->findAll();
        $municipios = $em->getRepository('AppBundle:Municipio')->findAll();
        $policlinicos  = $em->getRepository('AppBundle:Policlinico')->findAll();
        $data['recordsTotal'] = $em->createQuery('SELECT count(a) FROM AppBundle:Policlinico a ')->getSingleResult();



        return $this->render('Nomencladores/Policlinicos/policlinicos.html.twig' , [
            'provincias' => $provincias,
            'municipios' => $municipios,
            'policlinicos' => $policlinicos,
            'data' => $data
        ]);
    }

    /**
     * @Route("/dataTablePoliclinicosAjax", name="dataTablePoliclinicosAjax")
     */
    public function dataTablePoliclinicosAjaxAction()
    {
        $entityManager = $this->getDoctrine()->getManager();

        //DataSource del DataTable
        $dql = "SELECT a.id, p.nombre as provincia, m.nombre as municipio, a.tipoUnidad, a.nombre as policlinico, a.codigoUnidad, a.codigoUnidadFull FROM AppBundle:Policlinico a INNER JOIN a.municipio m INNER JOIN m.provincia p";
        $dqlTotalRecords = "SELECT count(a) FROM AppBundle:Policlinico a INNER JOIN a.municipio m INNER JOIN m.provincia p";
        $dqlCountFiltered = "SELECT count(a) FROM AppBundle:Policlinico a INNER JOIN a.municipio m INNER JOIN m.provincia p";

        //Esto es para armar el filtro que viene del front
        $sqlFilter = "";

        if (!empty($_GET['search']['value'])) {
            $strMainSearch = $_GET['search']['value'];

            $sqlFilter .= " (p.nombre  LIKE '%".$strMainSearch."%' OR "
                ."m.nombre  LIKE '%".$strMainSearch."%' OR "
                ."a.tipoUnidad  LIKE '%".$strMainSearch."%' OR "
                ."a.nombre  LIKE '%".$strMainSearch."%' OR "
                ."a.codigoUnidad  LIKE '%".$strMainSearch."%' OR "
                ."a.codigoUnidadFull LIKE '%".$strMainSearch."%') ";
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
                        case 'provincia': $strColSearch .= " p.nombre LIKE '%".$column['search']['value']."%' ";
                            break;
                        case 'municipio': $strColSearch .= " m.nombre LIKE '%".$column['search']['value']."%' ";
                            break;
                        case 'tipoUnidad':  $strColSearch .= " a.tipoUnidad LIKE '%".$column['search']['value']."%' ";
                            break;
                        case 'policlinico':  $strColSearch .= " a.nombre LIKE '%".$column['search']['value']."%' ";
                            break;
                        case 'codigoUnidad':  $strColSearch .= " a.codigoUnidad LIKE '%".$column['search']['value']."%' ";
                            break;
                        case 'codigoUnidadFull':  $strColSearch .= " a.codigoUnidadFull LIKE '%".$column['search']['value']."%' ";
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
                $value['provincia'],
                $value['municipio'],
                $value['tipoUnidad'],
                $value['policlinico'],
                $value['codigoUnidad'],
                $value['codigoUnidadFull'],
                $acciones = "<button class=\"btn btn-sm btn-info btn-icon-only rounded-circle editPoliclinico\"
                                                    type=\"button\" data-toggle=\"tooltip\"
                                                    data-placement=\"top\"
                                                    title=\"Modificar\">
                                        <span class=\"btn-inner--icon\"><i
                                                    class=\"fas fa-edit\"></i></span>
                                            </button>
                                            <button class=\"btn btn-sm btn-danger btn-icon-only rounded-circle borrarPoliclinico\"
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
     * @Route("/insertPoliclinico", name="insertPoliclinico")
     */
    public function insertPoliclinicoAction()
    {
        $peticion = Request::createFromGlobals();
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();

        $data = array(
            'municipio' => $peticion->request->get('municipio'),
            'nombre' => $peticion->request->get('nombre')
        );

        $resp = $em->getRepository('AppBundle:Policlinico')->masterAgregarPoliclinico($user, $data);

        if (is_string($resp)) {
            return new Response($resp);
        }

        return new Response('ok');
    }

    /**
     * @Route("/updatePoliclinico", name="updatePoliclinico")
     */
    public function updatePoliclinicoAction()
    {
        $peticion = Request::createFromGlobals();
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();

        $data = array(
            'id' => $peticion->request->get('id'),
            'municipio' => $peticion->request->get('municipio'),
            'nombre' => $peticion->request->get('nombre')
        );

        $resp = $em->getRepository('AppBundle:Policlinico')->masterModificarPoliclinico($user, $data);

        if (is_string($resp)) {
            return new Response($resp);
        }

        return new Response('ok');
    }

    /**
     * @Route("/deletePoliclinico", name="deletePoliclinico")
     */
    public function deletePoliclinicoAction()
    {
        $peticion = Request::createFromGlobals();
        $idPoliclinico = $peticion->request->get('id');
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();

        $resp  = $em->getRepository('AppBundle:Policlinico')->eliminarPoliclinico($idPoliclinico);

        if(is_string($resp)) {
            return new Response($resp);
        }

        //se crea la traza
        $dataTraza = array(
            'modulo' => 'Policlínico',
            'accion' => 'Eliminar',
            'descripcion' => 'Se eliminó el policlínico con el nombre: '.$resp->getNombre()
        );
        $traza = $em->getRepository('AppBundle:Traza')->guardarTraza($user,$dataTraza);

        if (is_string($traza)) {
            return new Response($traza);
        }

        return new Response('ok');
    }

}
