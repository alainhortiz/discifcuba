<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class TrazaController extends Controller
{
    /**
     * @Route("/trazas", name="trazas")
     */
    public function trazasAction()
    {
        $em = $this->getDoctrine()->getManager();

        $acciones = $em->getRepository('AppBundle:Traza')->obtenerAcciones();

        return $this->render('Nomencladores/Trazas/trazas.html.twig', [
            'acciones' => $acciones
        ]);
    }

    /**
     * @Route("/dataTableTrazasAjax", name="dataTableTrazasAjax")
     */
    public function dataTableTrazasAjaxAction()
    {
        $entityManager = $this->getDoctrine()->getManager();

        //DataSource del DataTable
        $dql = "SELECT t.modulo, t.accion, u.nombreCompleto as usuario, t.fecha, t.descripcion FROM AppBundle:Traza t INNER JOIN t.usuario u";
        $dqlTotalRecords = "SELECT count(t) FROM AppBundle:Traza t INNER JOIN t.usuario u";
        $dqlCountFiltered = "SELECT count(t) FROM AppBundle:Traza t INNER JOIN t.usuario u";

        //Esto es para armar el filtro que viene del front
        $sqlFilter = "";

        if (!empty($_GET['search']['value'])) {
            $strMainSearch = $_GET['search']['value'];

            $sqlFilter .= " (t.modulo  LIKE '%".$strMainSearch."%' OR "
                ."t.accion  LIKE '%".$strMainSearch."%' OR "
                ."u.nombreCompleto  LIKE '%".$strMainSearch."%' OR "
                ."t.fecha  LIKE '%".$strMainSearch."%' OR "
                ."t.descripcion LIKE '%".$strMainSearch."%') ";
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
                        case 'modulo': $strColSearch .= " t.modulo LIKE '%".$column['search']['value']."%' ";
                            break;
                        case 'accion': $strColSearch .= " t.accion LIKE '%".$column['search']['value']."%' ";
                            break;
                        case 'usuario': $strColSearch .= " u.nombreCompleto LIKE '%".$column['search']['value']."%' ";
                            break;
                        case 'fecha':  $strColSearch .= " t.fecha LIKE '%".$column['search']['value']."%' ";
                            break;
                        case 'descripcion':  $strColSearch .= " t.descripcion LIKE '%".$column['search']['value']."%' ";
                            break;
                    }
                }
            }
        }

        if (!empty($sqlFilter) && !empty($strColSearch)) {
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
                $value['modulo'],
                $value['accion'],
                $value['usuario'],
                $value['fecha']->format('d-m-Y'),
                $value['descripcion'],
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
     * @Route("/insertTraza", name="insertTraza")
     * @param $modulo
     * @param $accion
     * @param $descripcion
     */
    public function  insertTrazaAction($modulo , $accion, $descripcion)
    {
        $user = $this->getUser();

        $dataTraza = array(
            'modulo' => $modulo,
            'accion' => $accion,
            'descripcion' => $descripcion
        );
        $em = $this->getDoctrine()->getManager();
        $em->getRepository('AppBundle:Traza')-> guardarTraza($user,$dataTraza);
    }

    /**
     * @Route("/accionUsuario", name="accionUsuario")
     */
    public function accionUsuarioAction()
    {
        $peticion = Request::createFromGlobals();
        $em = $this->getDoctrine()->getManager();

        $accion = $peticion->request->get('accion');

        $graficosUsuarios = $em->getRepository('AppBundle:Traza')->obtenerAccionesUsuarios($accion);

        if (is_string($graficosUsuarios)) {
            return new Response($graficosUsuarios);
        }

        $result = json_encode($graficosUsuarios);
        return new JsonResponse($result);

    }


}
