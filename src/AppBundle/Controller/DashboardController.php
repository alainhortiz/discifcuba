<?php

namespace AppBundle\Controller;

use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;

class DashboardController extends Controller
{
    /**
     * @Route("/inicio", name="inicio")
     * @throws Exception
     */
    public function inicioAction()
    {
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();

        $pHombres = 0;
        $pMujeres = 0;
        $pOtros = 0;
        $notificacion = $em->getRepository('AppBundle:Notificacion')->verificarNotificacion($user);
        $totalEntrevistados = $em->getRepository('AppBundle:Encuesta')->obtenerTotalEntrevistados();
        $entrevistados = $em->getRepository('AppBundle:Encuesta')->obtenerEntrevistadosGenero();
        $entrevistadosProvincias = $em->getRepository('AppBundle:Encuesta')->obtenerProvincias();

        //Calcular totales y promedio para las cajas de textos
        if ($totalEntrevistados !== 0) {
            foreach ($entrevistados as $e) {
                switch ($e['genero']) {
                    case 'Hombre':
                        $pHombres = ($e['total'] * 100) / $totalEntrevistados;
                        $pHombres = number_format($pHombres,0);
                        break;
                    case 'Mujer':
                        $pMujeres = ($e['total'] * 100) / $totalEntrevistados;
                        $pMujeres = number_format($pMujeres,0);
                        break;
                    case 'Otro':
                        $pOtros = ($e['total'] * 100) / $totalEntrevistados;
                        $pOtros = number_format($pOtros,0);
                        break;
                }
            }
        }

        //Conformar gráfico de géneros
        $generoHombre = $this->conformarGenero('Hombre');
        $generoMujer = $this->conformarGenero('Mujer');
        $generoOtro = $this->conformarGenero('Otro');

        //Conformar gráfico de grado de independencia
        $entrevistadosGrados = $em->getRepository('AppBundle:Encuesta')->obtenerGrados();

        //Conformar gráfico de Funcion Afectada
        $funciones = $em->getRepository('AppBundle:FuncionAfectado')->obtenerNombreFunciones();
        $r1Funcion = $em->getRepository('AppBundle:Encuesta')->obtenerFuncionMenor18();
        $r2Funcion = $em->getRepository('AppBundle:Encuesta')->obtenerFuncion1929();
        $r3Funcion = $em->getRepository('AppBundle:Encuesta')->obtenerFuncion3059();
        $r4Funcion = $em->getRepository('AppBundle:Encuesta')->obtenerFuncion6069();
        $r5Funcion = $em->getRepository('AppBundle:Encuesta')->obtenerFuncionMayor70();

        $rango1Funcion = [];
        foreach ($funciones as $f){
            $rango1Funcion[] = $this->conformarFuncion($r1Funcion,$f['funcion']);
        }
        $rango2Funcion = [];
        foreach ($funciones as $f){
            $rango2Funcion[] = $this->conformarFuncion($r2Funcion,$f['funcion']);
        }
        $rango3Funcion = [];
        foreach ($funciones as $f){
            $rango3Funcion[] = $this->conformarFuncion($r3Funcion,$f['funcion']);
        }
        $rango4Funcion = [];
        foreach ($funciones as $f){
            $rango4Funcion[] = $this->conformarFuncion($r4Funcion,$f['funcion']);
        }
        $rango5Funcion = [];
        foreach ($funciones as $f){
            $rango5Funcion[] = $this->conformarFuncion($r5Funcion,$f['funcion']);
        }

        $session = new Session();

        if (!empty($notificacion)) {
            $session->set('notificacion', $notificacion[0]['total']);
        } else {
            $session->set('notificacion', '0');
        }

        //se crea la traza
        $dataTraza = array(
            'modulo' => 'Login',
            'accion' => 'Acceso',
            'descripcion' => 'Se accedió al sistema'
        );
        $em->getRepository('AppBundle:Traza')->guardarTraza($user, $dataTraza);

        return $this->render('Inicio/inicio.html.twig', [
            'totalEntrevistados' => $totalEntrevistados,
            'pHombres' => $pHombres,
            'pMujeres' => $pMujeres,
            'pOtros' => $pOtros,
            'entrevistadosProvincias' => $entrevistadosProvincias,
            'entrevistadosGrados' => $entrevistadosGrados,
            'generoHombre' => $generoHombre,
            'generoMujer' => $generoMujer,
            'generoOtro' => $generoOtro,
            'funciones' => $funciones,
            'rango1Funcion' => $rango1Funcion,
            'rango2Funcion' => $rango2Funcion,
            'rango3Funcion' => $rango3Funcion,
            'rango4Funcion' => $rango4Funcion,
            'rango5Funcion' => $rango5Funcion
        ]);
    }

    /**
     * @Route("/graficoProvincia", name="graficoProvincia")
     */
    public function graficoProvinciaAction()
    {
        $peticion = Request::createFromGlobals();
        $em = $this->getDoctrine()->getManager();

        $pHombres = 0;
        $pMujeres = 0;
        $pOtros = 0;
        $provincia = $peticion->request->get('provincia');
        $totalEntrevistados = $em->getRepository('AppBundle:Encuesta')->obtenerTotalEntrevistadosProvincia($provincia);
        $entrevistadosMunicipios = $em->getRepository('AppBundle:Encuesta')->obtenerMunicipios($provincia);
        $entrevistados = $em->getRepository('AppBundle:Encuesta')->obtenerEntrevistadosGeneroProvincia($provincia);

        //Calcular totales y promedio para las cajas de textos
        if ($totalEntrevistados !== 0) {
            foreach ($entrevistados as $e) {
                switch ($e['genero']) {
                    case 'Hombre':
                        $pHombres = ($e['total'] * 100) / $totalEntrevistados;
                        $pHombres = number_format($pHombres,0);
                        break;
                    case 'Mujer':
                        $pMujeres = ($e['total'] * 100) / $totalEntrevistados;
                        $pMujeres = number_format($pMujeres,0);
                        break;
                    case 'Otro':
                        $pOtros = ($e['total'] * 100) / $totalEntrevistados;
                        $pOtros = number_format($pOtros,0);
                        break;
                }
            }
        }

        //Conformar gráfico de géneros
        $generoHombre = $this->conformarGeneroProvincia('Hombre',$provincia);
        $generoMujer = $this->conformarGeneroProvincia('Mujer',$provincia);
        $generoOtro = $this->conformarGeneroProvincia('Otro',$provincia);

        //Conformar gráfico de grado de independencia
        $entrevistadosGrados = $em->getRepository('AppBundle:Encuesta')->obtenerGradosProvincia($provincia);

        //Conformar gráfico de Función Afectada
        $funciones = $em->getRepository('AppBundle:FuncionAfectado')->obtenerNombreFunciones();
        $r1Funcion = $em->getRepository('AppBundle:Encuesta')->obtenerFuncionMenor18Provincia($provincia);
        $r2Funcion = $em->getRepository('AppBundle:Encuesta')->obtenerFuncion1929Provincia($provincia);
        $r3Funcion = $em->getRepository('AppBundle:Encuesta')->obtenerFuncion3059Provincia($provincia);
        $r4Funcion = $em->getRepository('AppBundle:Encuesta')->obtenerFuncion6069Provincia($provincia);
        $r5Funcion = $em->getRepository('AppBundle:Encuesta')->obtenerFuncionMayor70Provincia($provincia);

        $rango1Funcion = [];
        foreach ($funciones as $f){
            $rango1Funcion[] = $this->conformarFuncion($r1Funcion,$f['funcion']);
        }
        $rango2Funcion = [];
        foreach ($funciones as $f){
            $rango2Funcion[] = $this->conformarFuncion($r2Funcion,$f['funcion']);
        }
        $rango3Funcion = [];
        foreach ($funciones as $f){
            $rango3Funcion[] = $this->conformarFuncion($r3Funcion,$f['funcion']);
        }
        $rango4Funcion = [];
        foreach ($funciones as $f){
            $rango4Funcion[] = $this->conformarFuncion($r4Funcion,$f['funcion']);
        }
        $rango5Funcion = [];
        foreach ($funciones as $f){
            $rango5Funcion[] = $this->conformarFuncion($r5Funcion,$f['funcion']);
        }

        $graficos = array(
            'entrevistadosMunicipios' => $entrevistadosMunicipios,
            'totalEntrevistados' => $totalEntrevistados,
            'pHombres' => $pHombres,
            'pMujeres' => $pMujeres,
            'pOtros' => $pOtros,
            'generoHombre' => $generoHombre,
            'generoMujer' => $generoMujer,
            'generoOtro' => $generoOtro,
            'entrevistadosGrados' => $entrevistadosGrados,
            'rango1Funcion' => $rango1Funcion,
            'rango2Funcion' => $rango2Funcion,
            'rango3Funcion' => $rango3Funcion,
            'rango4Funcion' => $rango4Funcion,
            'rango5Funcion' => $rango5Funcion
        );

        if (is_string($entrevistadosMunicipios)) {
            return new Response($entrevistadosMunicipios);
        }

        $result = json_encode($graficos);
        return new JsonResponse($result);

    }

    /**
     * @Route("/graficoMunicipio", name="graficoMunicipio")
     */
    public function graficoMunicipioAction()
    {
        $peticion = Request::createFromGlobals();
        $em = $this->getDoctrine()->getManager();

        $pHombres = 0;
        $pMujeres = 0;
        $pOtros = 0;
        $municipio = $peticion->request->get('municipio');
        $totalEntrevistados = $em->getRepository('AppBundle:Encuesta')->obtenerTotalEntrevistadosMunicipio($municipio);
        $entrevistados = $em->getRepository('AppBundle:Encuesta')->obtenerEntrevistadosgeneroMunicipio($municipio);

        //Calcular totales y promedio para las cajas de textos
        if ($totalEntrevistados !== 0) {
            foreach ($entrevistados as $e) {
                switch ($e['genero']) {
                    case 'Hombre':
                        $pHombres = ($e['total'] * 100) / $totalEntrevistados;
                        $pHombres = number_format($pHombres,0);
                        break;
                    case 'Mujer':
                        $pMujeres = ($e['total'] * 100) / $totalEntrevistados;
                        $pMujeres = number_format($pMujeres,0);
                        break;
                    case 'Otro':
                        $pOtros = ($e['total'] * 100) / $totalEntrevistados;
                        $pOtros = number_format($pOtros,0);
                        break;
                }
            }
        }

        //Conformar gráfico de géneros
        $generoHombre = $this->conformarGeneroMunicipio('Hombre',$municipio);
        $generoMujer = $this->conformarGeneroMunicipio('Mujer',$municipio);
        $generoOtro = $this->conformarGeneroMunicipio('Otro',$municipio);

        //Conformar gráfico de grado de independencia
        $entrevistadosGrados = $em->getRepository('AppBundle:Encuesta')->obtenerGradosMunicipio($municipio);

        //Conformar gráfico de Función Afectada
        $funciones = $em->getRepository('AppBundle:FuncionAfectado')->obtenerNombreFunciones();
        $r1Funcion = $em->getRepository('AppBundle:Encuesta')->obtenerFuncionMenor18Municipio($municipio);
        $r2Funcion = $em->getRepository('AppBundle:Encuesta')->obtenerFuncion1929Municipio($municipio);
        $r3Funcion = $em->getRepository('AppBundle:Encuesta')->obtenerFuncion3059Municipio($municipio);
        $r4Funcion = $em->getRepository('AppBundle:Encuesta')->obtenerFuncion6069Municipio($municipio);
        $r5Funcion = $em->getRepository('AppBundle:Encuesta')->obtenerFuncionMayor70Municipio($municipio);

        $rango1Funcion = [];
        foreach ($funciones as $f){
            $rango1Funcion[] = $this->conformarFuncion($r1Funcion,$f['funcion']);
        }
        $rango2Funcion = [];
        foreach ($funciones as $f){
            $rango2Funcion[] = $this->conformarFuncion($r2Funcion,$f['funcion']);
        }
        $rango3Funcion = [];
        foreach ($funciones as $f){
            $rango3Funcion[] = $this->conformarFuncion($r3Funcion,$f['funcion']);
        }
        $rango4Funcion = [];
        foreach ($funciones as $f){
            $rango4Funcion[] = $this->conformarFuncion($r4Funcion,$f['funcion']);
        }
        $rango5Funcion = [];
        foreach ($funciones as $f){
            $rango5Funcion[] = $this->conformarFuncion($r5Funcion,$f['funcion']);
        }

        $graficos = array(
            'totalEntrevistados' => $totalEntrevistados,
            'pHombres' => $pHombres,
            'pMujeres' => $pMujeres,
            'pOtros' => $pOtros,
            'generoHombre' => $generoHombre,
            'generoMujer' => $generoMujer,
            'generoOtro' => $generoOtro,
            'entrevistadosGrados' => $entrevistadosGrados,
            'rango1Funcion' => $rango1Funcion,
            'rango2Funcion' => $rango2Funcion,
            'rango3Funcion' => $rango3Funcion,
            'rango4Funcion' => $rango4Funcion,
            'rango5Funcion' => $rango5Funcion
        );

        if (is_string($entrevistados)) {
            return new Response($entrevistados);
        }

        $result = json_encode($graficos);
        return new JsonResponse($result);

    }

    private function conformarGenero($genero) {

        $em = $this->getDoctrine()->getManager();

        $rango1 = $em->getRepository('AppBundle:Encuesta')->obtenerGeneroMenor18($genero);
        $rango2 = $em->getRepository('AppBundle:Encuesta')->obtenerGenero1929($genero);
        $rango3 = $em->getRepository('AppBundle:Encuesta')->obtenerGenero3059($genero);
        $rango4 = $em->getRepository('AppBundle:Encuesta')->obtenerGenero6069($genero);
        $rango5 = $em->getRepository('AppBundle:Encuesta')->obtenerGeneroMayor70($genero);

        if (empty($rango1)) {
            $rango1 = 0;
        }
        if (empty($rango2)) {
            $rango2 = 0;
        }
        if (empty($rango3)) {
            $rango3 = 0;
        }
        if (empty($rango4)) {
            $rango4 = 0;
        }
        if (empty($rango5)) {
            $rango5 = 0;
        }

        return [$rango1, $rango2, $rango3, $rango4, $rango5];
    }

    private function conformarGeneroProvincia($genero,$provincia) {

        $em = $this->getDoctrine()->getManager();

        $rango1 = $em->getRepository('AppBundle:Encuesta')->obtenerGeneroMenor18Provincia($genero,$provincia);
        $rango2 = $em->getRepository('AppBundle:Encuesta')->obtenerGenero1929Provincia($genero,$provincia);
        $rango3 = $em->getRepository('AppBundle:Encuesta')->obtenerGenero3059Provincia($genero,$provincia);
        $rango4 = $em->getRepository('AppBundle:Encuesta')->obtenerGenero6069Provincia($genero,$provincia);
        $rango5 = $em->getRepository('AppBundle:Encuesta')->obtenerGeneroMayor70Provincia($genero,$provincia);

        if (empty($rango1)) {
            $rango1 = 0;
        }
        if (empty($rango2)) {
            $rango2 = 0;
        }
        if (empty($rango3)) {
            $rango3 = 0;
        }
        if (empty($rango4)) {
            $rango4 = 0;
        }
        if (empty($rango5)) {
            $rango5 = 0;
        }

        return [$rango1, $rango2, $rango3, $rango4, $rango5];
    }

    private function conformarGeneroMunicipio($genero,$municipio) {

        $em = $this->getDoctrine()->getManager();

        $rango1 = $em->getRepository('AppBundle:Encuesta')->obtenerGeneroMenor18Municipio($genero,$municipio);
        $rango2 = $em->getRepository('AppBundle:Encuesta')->obtenerGenero1929Municipio($genero,$municipio);
        $rango3 = $em->getRepository('AppBundle:Encuesta')->obtenerGenero3059Municipio($genero,$municipio);
        $rango4 = $em->getRepository('AppBundle:Encuesta')->obtenerGenero6069Municipio($genero,$municipio);
        $rango5 = $em->getRepository('AppBundle:Encuesta')->obtenerGeneroMayor70Municipio($genero,$municipio);

        if (empty($rango1)) {
            $rango1 = 0;
        }
        if (empty($rango2)) {
            $rango2 = 0;
        }
        if (empty($rango3)) {
            $rango3 = 0;
        }
        if (empty($rango4)) {
            $rango4 = 0;
        }
        if (empty($rango5)) {
            $rango5 = 0;
        }

        return [$rango1, $rango2, $rango3, $rango4, $rango5];
    }

    private function conformarFuncion($rFuncion,$funcion){
        $cantidad = 0;
        foreach ($rFuncion as $r){
            $funcionR = $r['funcion'];
            if ($funcion === $funcionR){
                $cantidad = $r['cantidad'];
            }
        }
        return $cantidad;
    }

}
