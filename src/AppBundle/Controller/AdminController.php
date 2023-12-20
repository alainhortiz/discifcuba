<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminController extends Controller
{
    /**
     * @Route("/gestionarUsuarios", name="gestionarUsuarios")
     */
    public function gestionarUsuariosAccion()
    {
        $em = $this->getDoctrine()->getManager();

        $usuarios = $em->getRepository('AppBundle:Usuario')->findAll();

        return $this->render('Nomencladores/Usuarios/gestionUsuarios.html.twig', array(
            'usuarios' => $usuarios
        ));
    }

    /**
     * @Route("/dataTableUsuariosAjax", name="dataTableUsuariosAjax")
     */
    public function dataTableUsuariosAjaxAction()
    {
        $entityManager = $this->getDoctrine()->getManager();

        //DataSource del DataTable
        $dql = "SELECT u.id, u.username, u.nombre, u.primerApellido, u.segundoApellido, p.nombre as profesion, n.nivel FROM AppBundle:Usuario u INNER JOIN u.profesion p INNER JOIN u.nivelAcceso n WHERE u.username <> 'system' AND u.isActive = true";
        $dqlTotalRecords = "SELECT count(u) FROM AppBundle:Usuario u INNER JOIN u.profesion p INNER JOIN u.nivelAcceso n WHERE u.username <> 'system' AND u.isActive = true";
        $dqlCountFiltered = "SELECT count(u) FROM AppBundle:Usuario u INNER JOIN u.profesion p INNER JOIN u.nivelAcceso n WHERE u.username <> 'system' AND u.isActive = true";

        //Esto es para armar el filtro que viene del front
        $sqlFilter = "";

        if (!empty($_GET['search']['value'])) {
            $strMainSearch = $_GET['search']['value'];

            $sqlFilter .= " (u.id  LIKE '%".$strMainSearch."%' OR "
                ."u.username  LIKE '%".$strMainSearch."%' OR "
                ."u.nombre  LIKE '%".$strMainSearch."%' OR "
                ."u.primerApellido  LIKE '%".$strMainSearch."%' OR "
                ."u.segundoApellido  LIKE '%".$strMainSearch."%' OR "
                ."p.nombre  LIKE '%".$strMainSearch."%' OR "
                ."n.nivel LIKE '%".$strMainSearch."%') ";
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
                        case 'id': $strColSearch .= " u.id LIKE '%".$column['search']['value']."%' ";
                            break;
                        case 'username': $strColSearch .= " u.username LIKE '%".$column['search']['value']."%' ";
                            break;
                        case 'nombre':  $strColSearch .= " u.nombre LIKE '%".$column['search']['value']."%' ";
                            break;
                        case 'primerApellido':  $strColSearch .= " u.primerApellido LIKE '%".$column['search']['value']."%' ";
                            break;
                        case 'segundoApellido':  $strColSearch .= " u.segundoApellido LIKE '%".$column['search']['value']."%' ";
                            break;
                        case 'profesion':  $strColSearch .= " p.nombre LIKE '%".$column['search']['value']."%' ";
                            break;
                        case 'nivel':  $strColSearch .= " n.nivel LIKE '%".$column['search']['value']."%' ";
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
                $value['username'],
                $value['nombre'],
                $value['primerApellido'],
                $value['segundoApellido'],
                $value['profesion'],
                $value['nivel'],
                $acciones = "<button class=\"btn btn-sm btn-info btn-icon-only rounded-circle editUsuario\"
                                                    type=\"button\" data-toggle=\"tooltip\"
                                                    data-placement=\"top\"
                                                    title=\"Modificar\">
                                                                                 <span class=\"btn-inner--icon\"><i
                                                                                             class=\"fas fa-edit\"></i></span>
                                            </button>"
                    . "<button class=\"btn btn-sm btn-warning btn-icon-only rounded-circle resetearClave\"
                                                    type=\"button\" data-toggle=\"tooltip\"
                                                    data-placement=\"top\"
                                                    title=\"Resetear\">
                                                                                 <span class=\"btn-inner--icon\"><i
                                                                                             class=\"fas fa-unlock\"></i></span>
                                            </button>"
                    .  "<button class=\"btn btn-sm btn-danger btn-icon-only rounded-circle borrarUsuario\"
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
     * @Route("/addUsuario", name="addUsuario")
     */
    public function addUsuarioAction()
    {
        $em = $this->getDoctrine()->getManager();

        $profesiones = $em->getRepository('AppBundle:Profesion')->findAll();
        $nivelAccesos = $em->getRepository('AppBundle:NivelAcceso')->findAll();
        $roles = $em->getRepository('AppBundle:Role')->findAll();

        return $this->render('Nomencladores/Usuarios/addUsuario.html.twig', array(
                'roles' => $roles,
                'profesiones' => $profesiones,
                'nivelAccesos' => $nivelAccesos)
        );
    }

    /**
     * @Route("/insertUsuario", name="insertUsuario")
     */
    public function insertUsuarioAction()
    {
        $peticion = Request::createFromGlobals();
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();

        $data = array(
            'username' => $peticion->request->get('username'),
            'nombre' => $peticion->request->get('nombre'),
            'primerApellido' => $peticion->request->get('primerApellido'),
            'segundoApellido' => $peticion->request->get('segundoApellido'),
            'profesion' => $peticion->request->get('profesion'),
            'nivelAcceso' => $peticion->request->get('nivelAcceso'),
            'roles' => $peticion->request->get('roles'),
            'supervisor' => $peticion->request->get('supervisor')
        );

        $usuario = $em->getRepository('AppBundle:Usuario')->agregarUsuario($data);

        if (is_string($usuario)) {
            return new Response($usuario);
        }

        //se crea la traza
        $dataTraza = array(
            'modulo' => 'Usuario',
            'accion' => 'Insertar',
            'descripcion' => 'Se insertó el usuario: ' . $data['nombre'] . ' ' . $data['primerApellido'] . ' ' . $data['segundoApellido']
        );
        $traza = $em->getRepository('AppBundle:Traza')->guardarTraza($user,$dataTraza);

        if (is_string($traza)) {
            return new Response($traza);
        }

        return new Response('ok');
    }

    /**
     * @Route("/editUsuario/{idUsuario}", name="editUsuario")
     */
    public function editUsuarioAction($idUsuario)
    {
        $em = $this->getDoctrine()->getManager();

        $usuario = $em->getRepository('AppBundle:Usuario')->find($idUsuario);
        $profesiones = $em->getRepository('AppBundle:Profesion')->findAll();
        $nivelAccesos = $em->getRepository('AppBundle:NivelAcceso')->findAll();
        $roles = $em->getRepository('AppBundle:Role')->findAll();

        return $this->render('Nomencladores/Usuarios/editUsuario.html.twig', array(
                'usuario' => $usuario,
                'profesiones' => $profesiones,
                'nivelAccesos' => $nivelAccesos,
                'roles' => $roles)
        );
    }

    /**
     * @Route("/updateUsuario", name="updateUsuario")
     */
    public function updateUsuarioAction()
    {
        $peticion = Request::createFromGlobals();
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();

        $data = array(
            'idUsuario' => $peticion->request->get('idUsuario'),
            'username' => $peticion->request->get('username'),
            'nombre' => $peticion->request->get('nombre'),
            'primerApellido' => $peticion->request->get('primerApellido'),
            'segundoApellido' => $peticion->request->get('segundoApellido'),
            'profesion' => $peticion->request->get('profesion'),
            'nivelAcceso' => $peticion->request->get('nivelAcceso'),
            'roles' => $peticion->request->get('roles'),
            'supervisor' => $peticion->request->get('supervisor')
        );

        $resp = $em->getRepository('AppBundle:Usuario')->modificarUsuario($data);

        if (is_string($resp)) {
            return new Response($resp);
        }

        //se crea la traza
        $dataTraza = array(
            'modulo' => 'Usuario',
            'accion' => 'Modificar',
            'descripcion' => 'Se modificó el usuario: ' . $data['nombre'] . ' ' . $data['primerApellido'] . ' ' . $data['segundoApellido']
        );
        $traza = $em->getRepository('AppBundle:Traza')->guardarTraza($user,$dataTraza);

        if (is_string($traza)) {
            return new Response($traza);
        }

        return new Response('ok');
    }

    /**
     * @Route("/deleteUsuario", name="deleteUsuario")
     */
    public function deleteUsuarioAccion()
    {
        $peticion = Request::createFromGlobals();
        $id = $peticion->request->get('id');
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();

        $resp = $em->getRepository('AppBundle:Usuario')->eliminarUsuario($id);

        if (is_string($resp)) {
            return new Response($resp);
        }

        //se crea la traza
        $dataTraza = array(
            'modulo' => 'Usuario',
            'accion' => 'Eliminar',
            'descripcion' => 'Se eliminó el usuario: ' . $resp->getNombreCompleto()
        );
        $traza = $em->getRepository('AppBundle:Traza')->guardarTraza($user,$dataTraza);

        if (is_string($traza)) {
            return new Response($traza);
        }

        return new Response('ok');
    }

    /**
     * @Route("/desactivarUsuario", name="desactivarUsuario")
     */
    public function desactivarUsuarioAccion()
    {
        $peticion = Request::createFromGlobals();
        $user = $this->getUser();
        $idUsuario = $peticion->request->get('idUsuario');
        $em = $this->getDoctrine()->getManager();

        $resp = $em->getRepository('AppBundle:Usuario')->eliminarUsuario($idUsuario);

        if (is_string($resp)) {
            return new Response($resp);
        }

        //se crea la traza
        $dataTraza = array(
            'modulo' => 'Usuario',
            'accion' => 'Eliminar',
            'descripcion' => 'Se eliminó el usuario: ' . $resp->getNombreCompleto()
        );
        $traza = $em->getRepository('AppBundle:Traza')->guardarTraza($user,$dataTraza);

        if (is_string($traza)) {
            return new Response($traza);
        }

        return new Response('ok');
    }

    /**
     * @Route("/resetearPassword", name="resetearPassword")
     */
    public function resetearPasswordAction()
    {
        $em = $this->getDoctrine()->getManager();
        $peticion = Request::createFromGlobals();
        $idUsuario = $peticion->request->get('idUsuario');
        $user = $this->getUser();

        $em->getRepository('AppBundle:Usuario')->resetearPassword($idUsuario);

        //se crea la traza
        $dataTraza = array(
            'modulo' => 'Usuario',
            'accion' => 'Resetear',
            'descripcion' => 'Se eliminó el usuario: ' . $user->getNombreCompleto()
        );
        $traza = $em->getRepository('AppBundle:Traza')->guardarTraza($user,$dataTraza);

        if (is_string($traza)) {
            return new Response($traza);
        }

        return new Response('ok');

    }

    /**
     * @Route("/cambiarPassword", name="cambiarPassword")
     */
    public function cambiarPasswordAction()
    {
        $em = $this->getDoctrine()->getManager();
        $peticion = Request::createFromGlobals();
        $idUsuario = $peticion->request->get('idUsuario');
        $passNew = $peticion->request->get('passNew');
        $user = $this->getUser();

        $usuario = $em->getRepository('AppBundle:Usuario')->find($idUsuario);

        $resp = $em->getRepository('AppBundle:Usuario')->cambiarPassword($idUsuario, $passNew);
        if (is_string($resp)) {
            return new Response($resp);
        }

        //se crea la traza
        $dataTraza = array(
            'modulo' => 'Usuario',
            'accion' => 'Resetear',
            'descripcion' => 'Se cambió el password del usuario: ' . $usuario->getNombreCompleto()
        );
        $traza = $em->getRepository('AppBundle:Traza')->guardarTraza($user,$dataTraza);

        if (is_string($traza)) {
            return new Response($traza);
        }

        return new Response('ok');
    }

}
