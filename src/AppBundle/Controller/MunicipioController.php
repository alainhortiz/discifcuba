<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class MunicipioController extends Controller
{
    /**
     * @Route("/gestionarMunicipios", name="gestionarMunicipios")
     */
    public function gestionarMunicipiosAction()
    {
        $em = $this->getDoctrine()->getManager();
        $provincias = $em->getRepository('AppBundle:Provincia')->findAll();
        $municipios = $em->getRepository('AppBundle:Municipio')->findAll();

        return $this->render('Nomencladores/Municipios/municipios.html.twig', array(
            'provincias' => $provincias,
            'municipios' => $municipios
        ));
    }

    /**
     * @Route("/dataTableMunicipiosAjax", name="dataTableMunicipiosAjax")
     */
    public function dataTableMunicipiosAjaxAction()
    {
        $entityManager = $this->getDoctrine()->getManager();

        //DataSource del DataTable
        $dql = "SELECT m.id, p.nombre as provincia, m.codigo, m.nombre as municipio FROM AppBundle:Municipio m INNER JOIN m.provincia p";
        $dqlTotalRecords = "SELECT count(m) FROM AppBundle:Municipio m INNER JOIN m.provincia p";
        $dqlCountFiltered = "SELECT count(m) FROM AppBundle:Municipio m INNER JOIN m.provincia p";

        //Esto es para armar el filtro que viene del front
        $sqlFilter = "";

        if (!empty($_GET['search']['value'])) {
            $strMainSearch = $_GET['search']['value'];

            $sqlFilter .= " (m.id  LIKE '%".$strMainSearch."%' OR "
                ."p.nombre  LIKE '%".$strMainSearch."%' OR "
                ."m.codigo  LIKE '%".$strMainSearch."%' OR "
                ."m.nombre LIKE '%".$strMainSearch."%') ";
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
                        case 'id': $strColSearch .= " m.id LIKE '%".$column['search']['value']."%' ";
                            break;
                        case 'provincia': $strColSearch .= " p.nombre LIKE '%".$column['search']['value']."%' ";
                            break;
                        case 'codigo': $strColSearch .= " m.codigo LIKE '%".$column['search']['value']."%' ";
                            break;
                        case 'municipio':  $strColSearch .= " m.nombre LIKE '%".$column['search']['value']."%' ";
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
                $value['codigo'],
                $value['municipio'],
                $acciones = "<button class=\"btn btn-sm btn-info btn-icon-only rounded-circle editMunicipio\"
                                                    type=\"button\" data-toggle=\"tooltip\"
                                                    data-placement=\"top\"
                                                    title=\"Modificar\">
                                        <span class=\"btn-inner--icon\"><i
                                                    class=\"fas fa-edit\"></i></span>
                                            </button>"
                    . "<button class=\"btn btn-sm btn-danger btn-icon-only rounded-circle borrarMunicipio\"
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
     * @Route("/insertMunicipio", name="insertMunicipio")
     */
    public function insertMunicipioAction()
    {
        $peticion = Request::createFromGlobals();
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();

        $data = array(
            'codigo' => $peticion->request->get('codigo'),
            'nombre' => $peticion->request->get('nombre'),
            'idProvincia' => $peticion->request->get('idProvincia')
        );

        $municipio = $em->getRepository('AppBundle:Municipio')->agregarMunicipio($data);

        if (is_string($municipio)) {
            return new Response($municipio);
        }

        //se crea la traza
        $dataTraza = array(
            'modulo' => 'Municipio',
            'accion' => 'Insertar',
            'descripcion' => 'Se insertó un nuevo municipio con el nombre: ' . $data['nombre']
        );
        $traza = $em->getRepository('AppBundle:Traza')->guardarTraza($user,$dataTraza);

        if (is_string($traza)) {
            return new Response($traza);
        }

        return new Response('ok');
    }

    /**
     * @Route("/updateMunicipio", name="updateMunicipio")
     */
    public function updateMunicipioAction()
    {
        $peticion = Request::createFromGlobals();
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();

        $data = array(
            'id' => $peticion->request->get('id'),
            'nombre' => $peticion->request->get('nombre'),
            'codigo' => $peticion->request->get('codigo'),
            'idProvincia' => $peticion->request->get('idProvincia')
        );

        $municipio = $em->getRepository('AppBundle:Municipio')->modificarMunicipio($data);

        if (is_string($municipio)) {
            return new Response($municipio);
        }

        //se crea la traza
        $dataTraza = array(
            'modulo' => 'Municipio',
            'accion' => 'Modificar',
            'descripcion' => 'Se modificó el municipio con el nombre: ' . $data['nombre']
        );
        $traza = $em->getRepository('AppBundle:Traza')->guardarTraza($user,$dataTraza);

        if (is_string($traza)) {
            return new Response($traza);
        }

        return new Response('ok');

    }

    /**
     * @Route("/deleteMunicipio", name="deleteMunicipio")
     */
    public function deleteMunicipioAction()
    {
        $peticion = Request::createFromGlobals();
        $id = $peticion->request->get('id');
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();

        $municipio = $em->getRepository('AppBundle:Municipio')->eliminarMunicipio($id);

        if (is_string($municipio)) {
            return new Response($municipio);
        }

        //se crea la traza
        $dataTraza = array(
            'modulo' => 'Municipio',
            'accion' => 'Eliminar',
            'descripcion' => 'Se eliminó el municipio con el nombre: ' . $municipio->getNombre()
        );
        $traza = $em->getRepository('AppBundle:Traza')->guardarTraza($user,$dataTraza);

        if (is_string($traza)) {
            return new Response($traza);
        }

        return new Response('ok');
    }
}
