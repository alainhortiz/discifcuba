<?php

namespace AppBundle\Controller;

use AppBundle\ExportacionExcel\ExportadorExcel;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class ExportController extends Controller
{
    /**
     * @Route("/reporteDominio", name="reporteDominio")
     */
    public function reporteDominioAction()
    {
        $em = $this->getDoctrine()->getManager();
        $municipios = $em->getRepository('AppBundle:Municipio')->findAll();
        $provincias = $em->getRepository('AppBundle:Provincia')->findAll();
        $policlinicos = $em->getRepository('AppBundle:Policlinico')->findAll();
        $dominios = $em->getRepository('AppBundle:TipoDominio')->findAll();

        return $this->render('Reportes/buscarDominio.html.twig', array(
            'municipios' => $municipios,
            'provincias' => $provincias,
            'policlinicos' => $policlinicos,
            'dominios' => $dominios,
            'noExiste' => 0
        ));

    }

    /**
     * @Route("/exportarDominio/{idPoliclinico}/{year}/{idDominio}", name="exportarDominio")
     */
    public function exportarDominioAction($idPoliclinico, $year, $idDominio)
    {

        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();

        $municipios = $em->getRepository('AppBundle:Municipio')->findAll();
        $provincias = $em->getRepository('AppBundle:Provincia')->findAll();
        $policlinicos = $em->getRepository('AppBundle:Policlinico')->findAll();
        $dominios = $em->getRepository('AppBundle:TipoDominio')->findAll();

        $entrevistados = $em->getRepository('AppBundle:Encuesta')->obtenerEntrevistados($idPoliclinico, $year);

        if ((int)$entrevistados === 0) {
            return $this->render('Reportes/buscarDominio.html.twig', array(
                'municipios' => $municipios,
                'provincias' => $provincias,
                'policlinicos' => $policlinicos,
                'dominios' => $dominios,
                'noExiste' => 1
            ));
        }

        $policlinico = $em->getRepository('AppBundle:Policlinico')->findOneBy(array('id' => $idPoliclinico));
        $dominio = $em->getRepository('AppBundle:TipoDominio')->findOneBy(array('id' => $idDominio));
        $noTabla = $em->getRepository('AppBundle:Pregunta')->obtenerTotalPreguntas($idDominio);

        $username = $user->getUserName();
        $nombreDocumento = "Tabla " . $dominio->getId() . ". " . $dominio->getNombre();
        $encabezado = "";

        switch ($dominio->getId()) {
            case 1:
                $titulo = "Número y porcentaje de personas con limitaciones para el " . strtolower($dominio->getNombre()) . ", según  categorías. Población, policlínico " . $policlinico->getNombre() . ". Año " . $year;
                $encabezado = "PERSONAS CON LIMITACIONES PARA EL APRENDIZAJE Y APLICACIÓN DEL CONOCIMIENTO";
                $nombreHoja = "Dominio T1-T3";
                break;
            case 2:
                $titulo = "Número y porcentaje de personas con limitaciones para realizar " . strtolower($dominio->getNombre()) . ", según  categorías. Población, policlínico " . $policlinico->getNombre() . ". Año " . $year;
                $encabezado = "PERSONAS CON LIMITACIONES PARA REALIZAR TAREAS Y DEMANDAS GENERALES";
                $nombreHoja = "Dominio T4-T6";
                break;
            case 3:
                $titulo = "Número y porcentaje de personas con limitaciones para conversar y utilizar aparatos y técnicas de comunicación, según  categorías. Población, policlínico " . $policlinico->getNombre() . ". Año " . $year;
                $encabezado = "PERSONAS CON LIMITACIONES EN CONVERSACIÓN Y UTILIZACIÓN DE APARATOS Y TÉCNICAS DE COMUNICACIÓN";
                $nombreHoja = "Dominio T7-T8";
                break;
            case 4:
                $titulo = "Número y porcentaje de personas con limitaciones en la " . strtolower($dominio->getNombre()) . ", según  categorías. Población, policlínico " . $policlinico->getNombre() . ". Año " . $year;
                $encabezado = "PERSONAS CON LIMITACIONES EN LA MOVILIDAD";
                $nombreHoja = "Dominio T9-T20";
                break;
            case 5:
                $titulo = "Número y porcentaje de personas con limitaciones en el " . strtolower($dominio->getNombre()) . ", según  categorías. Población, policlínico " . $policlinico->getNombre() . ". Año " . $year;
                $encabezado = "PERSONAS CON LIMITACIONES EN EL AUTOCUIDADO";
                $nombreHoja = "Dominio T21-T26";
                break;
            case 6:
                $titulo = "Número y porcentaje de personas con limitaciones para realizar tareas del hogar, según  categorías. Población, policlínico " . $policlinico->getNombre() . ". Año " . $year;
                $encabezado = "PERSONAS CON LIMITACIONES PARA REALIZAR TAREAS DEL HOGAR";
                $nombreHoja = "Dominio T27-T30";
                break;
            case 7:
                $titulo = "Número y porcentaje de personas con limitaciones en la " . strtolower($dominio->getNombre()) . ", según  categorías. Población, policlínico " . $policlinico->getNombre() . ". Año " . $year;
                $encabezado = "PERSONAS CON LIMITACIONES EN LA INTERACCIÓN Y RELACIONES INTERPERSONALES";
                $nombreHoja = "Dominio T31-T35";
                break;
            case 8:
                $titulo = "Número y porcentaje de personas con limitaciones en el trabajo, empleo y la educación, según  categorías. Población, policlínico " . $policlinico->getNombre() . ". Año " . $year;
                $encabezado = "PERSONAS CON LIMITACIONES EN EL TRABAJO Y EMPLEO.";
                $nombreHoja = "Dominio T36-T39";
                break;
            case 8.1:
                $titulo = "Número y porcentaje de personas con limitaciones en el área económica, según  categorías. Población, policlínico " . $policlinico->getNombre() . ". Año " . $year;
                $encabezado = "PERSONAS CON LIMITACIONES EN EL ÁREA ECONÓMICA.";
                $nombreHoja = "Dominio T40-T45";
                break;
            case 9:
                $titulo = "Número y porcentaje de personas con limitaciones en la en la vida comunitaria, según  categorías. Población, policlínico " . $policlinico->getNombre() . ". Año " . $year;
                $encabezado = "PERSONAS CON LIMITACIONES EN LA VIDA COMUNITARIA";
                $nombreHoja = "Dominio T46-T53";
                break;
        }

        $limitaciones = $em->getRepository('AppBundle:Encuesta')->obtenerDominios($idPoliclinico, $year, $idDominio);
        $respuestas = $em->getRepository('AppBundle:Encuesta')->obtenerDominiosRepuestas($idPoliclinico, $year, $idDominio);

        $total = 0;
        foreach ($limitaciones as $lim) {
            $total += (int)$lim['total'];
        }

        $resp = new ArrayCollection();
        if ($total !== 0) {
            foreach ($limitaciones as $lim) {
                $porcientoLimitacion = 0;
                $pregunta = $lim['pregunta'];
                $numeroNoAplica = 0;
                $porcientoNoAplica = 0;
                $numeroNinguna = 0;
                $porcientoNinguna = 0;
                $numeroLeve = 0;
                $porcientoLeve = 0;
                $numeroModerada = 0;
                $porcientoModerada = 0;
                $numeroSevera = 0;
                $porcientoSevera = 0;
                $numeroNoHacer = 0;
                $porcientoNoHacer = 0;
                foreach ($respuestas as $r) {
                    if ($pregunta === $r['pregunta']) {
                        switch ((int)$r['respuesta']) {
                            case -1:
                                $numeroNoAplica += (int)$r['total'];
                                break;
                            case 4:
                                $numeroNinguna += (int)$r['total'];
                                break;
                            case 3:
                                $numeroLeve += (int)$r['total'];
                                break;
                            case 2:
                                $numeroModerada += (int)$r['total'];
                                break;
                            case 1:
                                $numeroSevera += (int)$r['total'];
                                break;
                            case 0:
                                $numeroNoHacer += (int)$r['total'];
                                break;
                        }
                    }
                }
                if ((int)$lim['total'] !== 0) {
                    $porcientoLimitacion = ((int)$lim['total'] / $total) * 100;
                    if ($numeroNoAplica !== -1) {
                        $porcientoNoAplica = ($numeroNoAplica / (int)$lim['total']) * 100;
                    }
                    if ($numeroNinguna !== 0) {
                        $porcientoNinguna = ($numeroNinguna / (int)$lim['total']) * 100;
                    }
                    if ($numeroLeve !== 0) {
                        $porcientoLeve = ($numeroLeve / (int)$lim['total']) * 100;
                    }
                    if ($numeroModerada !== 0) {
                        $porcientoModerada = ($numeroModerada / (int)$lim['total']) * 100;
                    }
                    if ($numeroSevera !== 0) {
                        $porcientoSevera = ($numeroSevera / (int)$lim['total']) * 100;
                    }
                    if ($numeroNoHacer !== 0) {
                        $porcientoNoHacer = ($numeroNoHacer / (int)$lim['total']) * 100;
                    }
                }
                $resp->add(array(
                    'pregunta' => $lim['pregunta'],
                    'numeroNoAplica' => $numeroNoAplica,
                    'porcientoNoAplica' => $porcientoNoAplica,
                    'numeroNinguna' => $numeroNinguna,
                    'porcientoNinguna' => $porcientoNinguna,
                    'numeroLeve' => $numeroLeve,
                    'porcientoLeve' => $porcientoLeve,
                    'numeroModerada' => $numeroModerada,
                    'porcientoModerada' => $porcientoModerada,
                    'numeroSevera' => $numeroSevera,
                    'porcientoSevera' => $porcientoSevera,
                    'numeroNoHacer' => $numeroNoHacer,
                    'porcientoNoHacer' => $porcientoNoHacer,
                    'numeroTotal' => (int)$lim['total'],
                    'porcientoTotal' => $porcientoLimitacion,
                ));
            }
        }

        $excel = new ExportadorExcel();
        $excel->exportarDominio($username, $titulo, $nombreHoja, $nombreDocumento, $encabezado, $resp, $entrevistados, $noTabla);

    }

    /**
     * @Route("/reporteSexoEdad", name="reporteSexoEdad")
     */
    public function reporteSexoEdadAction()
    {
        $em = $this->getDoctrine()->getManager();
        $municipios = $em->getRepository('AppBundle:Municipio')->findAll();
        $provincias = $em->getRepository('AppBundle:Provincia')->findAll();
        $policlinicos = $em->getRepository('AppBundle:Policlinico')->findAll();

        return $this->render('Reportes/buscarSexoEdad.html.twig', array(
            'municipios' => $municipios,
            'provincias' => $provincias,
            'policlinicos' => $policlinicos,
            'noExiste' => 0
        ));

    }

    /**
     * @Route("/exportarSexoEdad/{idPoliclinico}/{year}/{indicador}", name="exportarSexoEdad")
     */
    public function exportarSexoEdadAction($idPoliclinico, $year, $indicador)
    {
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();

        $municipios = $em->getRepository('AppBundle:Municipio')->findAll();
        $provincias = $em->getRepository('AppBundle:Provincia')->findAll();
        $policlinicos = $em->getRepository('AppBundle:Policlinico')->findAll();

        $entrevistados = $em->getRepository('AppBundle:Encuesta')->obtenerEntrevistados($idPoliclinico, $year);

        if ((int)$entrevistados === 0) {
            return $this->render('Reportes/buscarSexoEdad.html.twig', array(
                'municipios' => $municipios,
                'provincias' => $provincias,
                'policlinicos' => $policlinicos,
                'noExiste' => 1
            ));
        }

        $policlinico = $em->getRepository('AppBundle:Policlinico')->findOneBy(array('id' => $idPoliclinico));

        $noTabla = 0;
        $tituloExcel = '';
        $encabezado = '';
        $indicadores = new ArrayCollection();
        $respuestas = '';

        switch ($indicador) {
            case '1':
                $noTabla = 10;
                $tituloExcel = 'Grado de Indep';
                $encabezado = 'GRADO DE INDEPENDENCIA';
                $indicadores = $em->getRepository('AppBundle:Encuesta')->obtenerGradoIndependencia($idPoliclinico, $year);
                $respuestas = $em->getRepository('AppBundle:Encuesta')->obtenerGradoIndependenciaSexoEdad($idPoliclinico, $year);
                break;
            case '2':
                $noTabla = 11;
                $tituloExcel = 'Empleo actual';
                $encabezado = strtoupper($tituloExcel);
                $indicadores = $em->getRepository('AppBundle:Encuesta')->obtenerEmpleo($idPoliclinico, $year);
                $respuestas = $em->getRepository('AppBundle:Encuesta')->obtenerEmpleoSexoEdad($idPoliclinico, $year);
                break;
            case '3':
                $noTabla = 12;
                $tituloExcel = 'Funciones afectadas';
                $encabezado = strtoupper($tituloExcel);
                $indicadores = $em->getRepository('AppBundle:Encuesta')->obtenerFuncionAfectada($idPoliclinico, $year);
                $respuestas = $em->getRepository('AppBundle:Encuesta')->obtenerFuncionAfectadaSexoEdad($idPoliclinico, $year);
                break;
            case '4':
                $noTabla = 13;
                $tituloExcel = 'Factor de Riesgo';
                $encabezado = strtoupper($tituloExcel);
                $indicadores = $em->getRepository('AppBundle:Encuesta')->obtenerFactorRiesgo($idPoliclinico, $year);
                $respuestas = $em->getRepository('AppBundle:Encuesta')->obtenerFactorRiesgoSexoEdad($idPoliclinico, $year);
                break;
            case '5':
                $noTabla = 14;
                $tituloExcel = 'Sist afectados';
                $encabezado = 'SISTEMAS AFECTADOS';
                $indicadores = $em->getRepository('AppBundle:Encuesta')->obtenerSistemaAfectado($idPoliclinico, $year);
                $respuestas = $em->getRepository('AppBundle:Encuesta')->obtenerSistemaAfectadoSexoEdad($idPoliclinico, $year);
                break;

        }

        $username = $user->getUserName();
        $titulo = "Tabla " . $noTabla . ". " . $encabezado . " SEGÚN SEXO Y GRUPOS DE EDAD. POBLACIÓN, POLICLÍNICO " . strtoupper($policlinico->getNombre()) . ". Año " . $year;
        $nombreHoja = "Tabla " . $noTabla;
        $nombreDocumento = "Tabla " . $noTabla . ". " . $tituloExcel . " según sexo y grupo de edades";

        $resp = new ArrayCollection();
        foreach ($indicadores as $ind) {
            $totalM = 0;
            $totalF = 0;
            $fila = $ind['nombre'];
            $totalCeroCuatroM = 0;
            $totalCeroCuatroF = 0;
            $totalCincoNueveM = 0;
            $totalCincoNueveF = 0;
            $totalDiezCatorceM = 0;
            $totalDiezCatorceF = 0;
            $totalQuinceDieciochoM = 0;
            $totalQuinceDieciochoF = 0;
            $totalDiecinueveCincuentinueveM = 0;
            $totalDiecinueveCincuentinueveF = 0;
            $totalMayor59M = 0;
            $totalMayor59F = 0;
            //Analizando fila por fila
            foreach ($respuestas as $r) {
                if ($fila === $r['nombre']) {
                    if($r['sexo'] === 'Masculino'){
                        $totalM += (int) $r['total'];
                        if((int) $r['edad'] >= 0 && (int) $r['edad'] <= 4) {
                            $totalCeroCuatroM += (int) $r['total'];
                        }elseif ((int) $r['edad'] >= 5 && (int) $r['edad'] <= 9) {
                            $totalCincoNueveM += (int) $r['total'];
                        }elseif ((int) $r['edad'] >= 10 && (int) $r['edad'] <= 14) {
                            $totalDiezCatorceM += (int) $r['total'];
                        }elseif ((int) $r['edad'] >= 15 && (int) $r['edad'] <= 18) {
                            $totalQuinceDieciochoM += (int) $r['total'];
                        }elseif ((int) $r['edad'] >= 19 && (int) $r['edad'] <= 59) {
                            $totalDiecinueveCincuentinueveM += (int) $r['total'];
                        }elseif ((int) $r['edad'] >= 60) {
                            $totalMayor59M += (int) $r['total'];
                        }
                    }else {
                        $totalF += (int) $r['total'];
                        if((int) $r['edad'] >= 0 && (int) $r['edad'] <= 4) {
                            $totalCeroCuatroF += (int) $r['total'];
                        }elseif ((int) $r['edad'] >= 5 && (int) $r['edad'] <= 9) {
                            $totalCincoNueveF += (int) $r['total'];
                        }elseif ((int) $r['edad'] >= 10 && (int) $r['edad'] <= 14) {
                            $totalDiezCatorceF += (int) $r['total'];
                        }elseif ((int) $r['edad'] >= 15 && (int) $r['edad'] <= 18) {
                            $totalQuinceDieciochoF += (int) $r['total'];
                        }elseif ((int) $r['edad'] >= 19 && (int) $r['edad'] <= 59) {
                            $totalDiecinueveCincuentinueveF += (int) $r['total'];
                        }elseif ((int) $r['edad'] >= 60) {
                            $totalMayor59F += (int) $r['total'];
                        }
                    }
                }
            }
            $resp->add(array(
                'indicador' => $ind['nombre'],
                'totalCeroCuatroM' => $totalCeroCuatroM,
                'totalCincoNueveM' => $totalCincoNueveM,
                'totalDiezCatorceM' => $totalDiezCatorceM,
                'totalQuinceDieciochoM' => $totalQuinceDieciochoM,
                'totalDiecinueveCincuentinueveM' => $totalDiecinueveCincuentinueveM,
                'totalMayor59M' => $totalMayor59M,
                'totalM' => $totalM,
                'totalCeroCuatroF' => $totalCeroCuatroF,
                'totalCincoNueveF' => $totalCincoNueveF,
                'totalDiezCatorceF' => $totalDiezCatorceF,
                'totalQuinceDieciochoF' => $totalQuinceDieciochoF,
                'totalDiecinueveCincuentinueveF' => $totalDiecinueveCincuentinueveF,
                'totalMayor59F' => $totalMayor59F,
                'totalF' => $totalF,
            ));
        }

        $excel = new ExportadorExcel();
        $excel->exportarIndicadorSexoEdad($username, $titulo, $nombreHoja, $nombreDocumento, $encabezado, $resp, $entrevistados);

    }

    private function mesEspanol($mesIngles)
    {
        $mes = '';
        if ($mesIngles === 'January') {
            $mes = 'Enero';
        } else if ($mesIngles === 'February') {
            $mes = 'Febrero';
        } else if ($mesIngles === 'March') {
            $mes = 'Marzo';
        } else if ($mesIngles === 'April') {
            $mes = 'Abril';
        } else if ($mesIngles === 'May') {
            $mes = 'Mayo';
        } else if ($mesIngles === 'June') {
            $mes = 'Junio';
        } else if ($mesIngles === 'July') {
            $mes = 'Julio';
        } else if ($mesIngles === 'August') {
            $mes = 'Agosto';
        } else if ($mesIngles === 'September') {
            $mes = 'Septiembre';
        } else if ($mesIngles === 'October') {
            $mes = 'Octubre';
        } else if ($mesIngles === 'November') {
            $mes = 'Noviembre';
        } else if ($mesIngles === 'December') {
            $mes = 'Diciembre';
        }

        return $mes;
    }

}
