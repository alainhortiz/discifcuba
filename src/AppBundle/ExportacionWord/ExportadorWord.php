<?php

namespace AppBundle\ExportacionWord;


use DateTime;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\Style\Paper;
use PhpOffice\PhpWord\TemplateProcessor;


class ExportadorWord
{

    public function exportarDirector($dia, $mes, $proyecto)
    {
        $phpWord = new PhpWord();
        $document = $phpWord->loadTemplate('plantillas/ctroDirector.docx');

        //Asignar valores
        $actual = $proyecto->getProyecto()->getFechaInicio();
        $date = $actual->format('Y');
        $nombreCompleto = $proyecto->getPersona()->nombreCompleto();
        $ci = $proyecto->getPersona()->getCarnetIdentidad();
        $ciudadania = $proyecto->getPersona()->getCiudadania()->getNombre();
        //Comprobar si tiene NIT
        $nit = '';
        if ($proyecto->getPersona()->getIsNIT()) {
            $nit = 'y NIT ' . $ci;
        }
        $titulo = $proyecto->getProyecto()->getTitulo();
        $noContratoProyecto = $proyecto->getProyecto()->getNoContratoCanal();
        $year = $date;
        $noContrato = $proyecto->getNoContrato();
        $direccion = $proyecto->getPersona()->getDireccion();
        $profesion = $proyecto->getPersona()->getProfesion()->getNombre();
        $funcion = $proyecto->getCargo()->getFuncion();
        $categoria = $proyecto->getProyecto()->getTipo()->getNombre();
        //Comprobar si tiene registro del creador
        $registroCreador = '';
        if ($proyecto->getPersona()->getNoRegistroCreador()) {
            $registroCreador = 'inscripto en el Registro del Creador con en número de carnet ' . $proyecto->getPersona()->getNoRegistroCreador() . ', ';
        }
        $cantEmisiones = $proyecto->getProyecto()->getEmisiones();
        $totalPresupuesto = $proyecto->getPresupuestoTotal();
        $totalPresupuestoLetra = $this->convertirNumeroLetra($totalPresupuesto);
        $totalCargo = $proyecto->getPresupuestoCargo();
        $totalCargoLetra = $this->convertirNumeroLetra($totalCargo);
        $totalOtro = $proyecto->getPresupuestoOtroIngreso();
        $totalOtroLetra = $this->convertirNumeroLetra($totalOtro);
        $productor = $proyecto->getProyecto()->getNombresProductorGeneral();

        //Sustitucion de los keys q estan en la plantilla
        $document->setValue('nombre_persona', ucwords($nombreCompleto));
        $document->setValue('carnet_identidad', $ci);
        $document->setValue('ciudadania', strtolower($ciudadania));
        $document->setValue('profesion', ucwords($profesion));
        $document->setValue('función_concepto', $funcion);
        $document->setValue('registro_creador', $registroCreador);
        $document->setValue('nit', $nit);
        $document->setValue('titulo', ucwords($titulo));
        $document->setValue('no_contrato_proyecto', $noContratoProyecto);
        $document->setValue('dia', $dia);
        $document->setValue('mes', strtolower($mes));
        $document->setValue('year', $year);
        $document->setValue('direccion', ucwords($direccion));
        $document->setValue('no_contrato', $noContrato);
        $document->setValue('cantidad_emisiones', $cantEmisiones);
        $document->setValue('categoria_proyecto', ucwords($categoria));
        $document->setValue('total_presupuesto', $totalPresupuesto);
        $document->setValue('total_presupuesto_letra', strtolower($totalPresupuestoLetra));
        $document->setValue('total_cargo', $totalCargo);
        $document->setValue('total_cargo_letra', strtolower($totalCargoLetra));
        $document->setValue('total_otros', $totalOtro);
        $document->setValue('total_otros_letra', strtolower($totalOtroLetra));
        $document->setValue('productor_general', ucwords($productor));

        $file = 'CttoNo. ' . $noContrato . ' DIRECTOR ' . ucwords($nombreCompleto) . ' ' . ucwords($titulo) . ' ' . $year . '.docx';
        header('Content-Disposition: attachment; filename="' . $file . '"');
        $document->saveAs("php://output");

        exit;
    }

    public function exportarProductor($dia, $mes, $proyecto)
    {
        $phpWord = new PhpWord();
        $document = $phpWord->loadTemplate('plantillas/ctroProductor.docx');

        //Asignar valores
        $actual = $proyecto->getProyecto()->getFechaInicio();
        $date = $actual->format('Y');
        $nombreCompleto = $proyecto->getPersona()->nombreCompleto();
        $ci = $proyecto->getPersona()->getCarnetIdentidad();
        $ciudadania = $proyecto->getPersona()->getCiudadania()->getNombre();
        //Comprobar si tiene NIT
        $nit = '';
        if ($proyecto->getPersona()->getIsNIT()) {
            $nit = 'y NIT ' . $ci;
        }
        $titulo = $proyecto->getProyecto()->getTitulo();
        $noContratoProyecto = $proyecto->getProyecto()->getNoContratoCanal();
        $year = $date;
        $noContrato = $proyecto->getNoContrato();
        $direccion = $proyecto->getPersona()->getDireccion();
        $profesion = $proyecto->getPersona()->getProfesion()->getNombre();
        $funcion = $proyecto->getCargo()->getFuncion();
        $categoria = $proyecto->getProyecto()->getTipo()->getNombre();
        //Comprobar si tiene registro del creador
        $registroCreador = '';
        if ($proyecto->getPersona()->getNoRegistroCreador()) {
            $registroCreador = 'inscripto en el Registro del Creador con en número de carnet ' . $proyecto->getPersona()->getNoRegistroCreador() . ', ';
        }
        $cantEmisiones = $proyecto->getProyecto()->getEmisiones();
        $totalPresupuesto = $proyecto->getPresupuestoTotal();
        $totalPresupuestoLetra = $this->convertirNumeroLetra($totalPresupuesto);
        $totalCargo = $proyecto->getPresupuestoCargo();
        $totalCargoLetra = $this->convertirNumeroLetra($totalCargo);
        $totalOtro = $proyecto->getPresupuestoOtroIngreso();
        $totalOtroLetra = $this->convertirNumeroLetra($totalOtro);
        $director = $proyecto->getProyecto()->getNombresDirectorGeneral();

        //Sustitucion de los keys q estan en la plantilla
        $document->setValue('nombre_persona', ucwords($nombreCompleto));
        $document->setValue('carnet_identidad', $ci);
        $document->setValue('ciudadania', strtolower($ciudadania));
        $document->setValue('profesion', ucwords($profesion));
        $document->setValue('función_concepto', $funcion);
        $document->setValue('registro_creador', $registroCreador);
        $document->setValue('nit', $nit);
        $document->setValue('titulo', ucwords($titulo));
        $document->setValue('no_contrato_proyecto', $noContratoProyecto);
        $document->setValue('dia', $dia);
        $document->setValue('mes', strtolower($mes));
        $document->setValue('year', $year);
        $document->setValue('direccion', ucwords($direccion));
        $document->setValue('no_contrato', $noContrato);
        $document->setValue('cantidad_emisiones', $cantEmisiones);
        $document->setValue('categoria_proyecto', ucwords($categoria));
        $document->setValue('total_presupuesto', $totalPresupuesto);
        $document->setValue('total_presupuesto_letra', strtolower($totalPresupuestoLetra));
        $document->setValue('total_cargo', $totalCargo);
        $document->setValue('total_cargo_letra', strtolower($totalCargoLetra));
        $document->setValue('total_otros', $totalOtro);
        $document->setValue('total_otros_letra', strtolower($totalOtroLetra));
        $document->setValue('director_general', ucwords($director));

        $file = 'CttoNo. ' . $noContrato . ' PRODUCTOR ' . ucwords($nombreCompleto) . ' ' . ucwords($titulo) . ' ' . $year . '.docx';
        header('Content-Disposition: attachment; filename="' . $file . '"');
        $document->saveAs("php://output");

        exit;
    }

    public function exportarGuionista($dia, $mes, $proyecto)
    {
        $phpWord = new PhpWord();
        $document = $phpWord->loadTemplate('plantillas/ctroGuionista.docx');

        //Asignar valores
        $actual = $proyecto->getProyecto()->getFechaInicio();
        $date = $actual->format('Y');
        $nombreCompleto = $proyecto->getPersona()->nombreCompleto();
        $ci = $proyecto->getPersona()->getCarnetIdentidad();
        $ciudadania = $proyecto->getPersona()->getCiudadania()->getNombre();
        //Comprobar si tiene NIT
        $nit = '';
        if ($proyecto->getPersona()->getIsNIT()) {
            $nit = 'y NIT ' . $ci;
        }
        $titulo = $proyecto->getProyecto()->getTitulo();
        $noContratoProyecto = $proyecto->getProyecto()->getNoContratoCanal();
        $year = $date;
        $noContrato = $proyecto->getNoContrato();
        $direccion = $proyecto->getPersona()->getDireccion();
        $profesion = $proyecto->getPersona()->getProfesion()->getNombre();
        $concepto = $proyecto->getCargo()->getNombre();
        $funcion = $proyecto->getCargo()->getFuncion();
        $categoria = $proyecto->getProyecto()->getTipo()->getNombre();
        //Comprobar si tiene registro del creador
        $registroCreador = '';
        if ($proyecto->getPersona()->getNoRegistroCreador()) {
            $registroCreador = 'inscripto en el Registro del Creador con en número de carnet ' . $proyecto->getPersona()->getNoRegistroCreador() . ', ';
        }
        $cantEmisiones = $proyecto->getProyecto()->getEmisiones();
        $formato = $proyecto->getProyecto()->getFormato()->getNombre();
        $duracion = $proyecto->getProyecto()->getTiempoPantalla();
        $etapaPreFilmacion = $proyecto->getProyecto()->getEtapaPreFilmacion();
        $etapaFilmacion = $proyecto->getProyecto()->getEtapaFilmacion();
        $etapaPostFilmacion = $proyecto->getProyecto()->getEtapaPostFilmacion();
        $totalPresupuesto = $proyecto->getPresupuestoTotal();
        $totalPresupuestoLetra = $this->convertirNumeroLetra($totalPresupuesto);
        $totalCargo = $proyecto->getPresupuestoCargo();
        $totalCargoLetra = $this->convertirNumeroLetra($totalCargo);
        $totalOtro = $proyecto->getPresupuestoOtroIngreso();
        $totalOtroLetra = $this->convertirNumeroLetra($totalOtro);
        $productor = $proyecto->getProyecto()->getNombresProductorGeneral();
        $director = $proyecto->getProyecto()->getNombresDirectorGeneral();

        //Sustitucion de los keys q estan en la plantilla
        $document->setValue('nombre_persona', ucwords($nombreCompleto));
        $document->setValue('carnet_identidad', $ci);
        $document->setValue('ciudadania', strtolower($ciudadania));
        $document->setValue('profesion', ucwords($profesion));
        $document->setValue('concepto', ucwords($concepto));
        $document->setValue('función_concepto', $funcion);
        $document->setValue('registro_creador', $registroCreador);
        $document->setValue('nit', $nit);
        $document->setValue('titulo', ucwords($titulo));
        $document->setValue('no_contrato_proyecto', $noContratoProyecto);
        $document->setValue('dia', $dia);
        $document->setValue('mes', strtolower($mes));
        $document->setValue('year', $year);
        $document->setValue('direccion', ucwords($direccion));
        $document->setValue('no_contrato', $noContrato);
        $document->setValue('cantidad_emisiones', $cantEmisiones);
        $document->setValue('formato', $formato);
        $document->setValue('duracion_pantalla', $duracion);
        $document->setValue('pre_grabacion', $etapaPreFilmacion);
        $document->setValue('grabacion', $etapaFilmacion);
        $document->setValue('post_grabacion', $etapaPostFilmacion);
        $document->setValue('categoria_proyecto', ucwords($categoria));
        $document->setValue('total_presupuesto', $totalPresupuesto);
        $document->setValue('total_presupuesto_letra', strtolower($totalPresupuestoLetra));
        $document->setValue('total_cargo', $totalCargo);
        $document->setValue('total_cargo_letra', strtolower($totalCargoLetra));
        $document->setValue('total_otros', $totalOtro);
        $document->setValue('total_otros_letra', strtolower($totalOtroLetra));
        $document->setValue('productor_general', ucwords($productor));
        $document->setValue('director_general', ucwords($director));

        $file = 'CttoNo. ' . $noContrato . ' ' . strtoupper($concepto) . ' ' . ucwords($nombreCompleto) . ' ' . ucwords($titulo) . ' ' . $year . '.docx';
        header('Content-Disposition: attachment; filename="' . $file . '"');
        $document->saveAs("php://output");

        exit;
    }

    public function exportarActor($dia, $mes, $proyecto)
    {
        $phpWord = new PhpWord();
        $document = $phpWord->loadTemplate('plantillas/ctroActor.docx');

        //Asignar valores
        $actual = $proyecto->getProyecto()->getFechaInicio();
        $date = $actual->format('Y');
        $nombreCompleto = $proyecto->getPersona()->nombreCompleto();
        $ci = $proyecto->getPersona()->getCarnetIdentidad();
        $ciudadania = $proyecto->getPersona()->getCiudadania()->getNombre();
        //Comprobar si tiene NIT
        $nit = '';
        if ($proyecto->getPersona()->getIsNIT()) {
            $nit = 'y NIT ' . $ci;
        }
        $titulo = $proyecto->getProyecto()->getTitulo();
        $noContratoProyecto = $proyecto->getProyecto()->getNoContratoCanal();
        $year = $date;
        $noContrato = $proyecto->getNoContrato();
        $direccion = $proyecto->getPersona()->getDireccion();
        $profesion = $proyecto->getPersona()->getProfesion()->getNombre();
        $concepto = $proyecto->getCargo()->getNombre();
        $funcion = $proyecto->getCargo()->getFuncion();
        $categoria = $proyecto->getProyecto()->getTipo()->getNombre();
        //Comprobar si tiene registro del creador
        $registroCreador = '';
        if ($proyecto->getPersona()->getNoRegistroCreador()) {
            $registroCreador = 'inscripto en el Registro del Creador con en número de carnet ' . $proyecto->getPersona()->getNoRegistroCreador() . ', ';
        }
        $cantEmisiones = $proyecto->getProyecto()->getEmisiones();
        $formato = $proyecto->getProyecto()->getFormato()->getNombre();
        $duracion = $proyecto->getProyecto()->getTiempoPantalla();
        $etapaPreFilmacion = $proyecto->getProyecto()->getEtapaPreFilmacion();
        $etapaFilmacion = $proyecto->getProyecto()->getEtapaFilmacion();
        $etapaPostFilmacion = $proyecto->getProyecto()->getEtapaPostFilmacion();
        $totalPresupuesto = $proyecto->getPresupuestoTotal();
        $totalPresupuestoLetra = $this->convertirNumeroLetra($totalPresupuesto);
        $totalCargo = $proyecto->getPresupuestoCargo();
        $totalCargoLetra = $this->convertirNumeroLetra($totalCargo);
        $totalOtro = $proyecto->getPresupuestoOtroIngreso();
        $totalOtroLetra = $this->convertirNumeroLetra($totalOtro);
        $productor = $proyecto->getProyecto()->getNombresProductorGeneral();
        $director = $proyecto->getProyecto()->getNombresDirectorGeneral();

        //Sustitucion de los keys q estan en la plantilla
        $document->setValue('nombre_persona', ucwords($nombreCompleto));
        $document->setValue('carnet_identidad', $ci);
        $document->setValue('ciudadania', strtolower($ciudadania));
        $document->setValue('profesion', strtoupper($profesion));
        $document->setValue('concepto', ucwords($concepto));
        $document->setValue('función_concepto', $funcion);
        $document->setValue('registro_creador', $registroCreador);
        $document->setValue('nit', $nit);
        $document->setValue('titulo', ucwords($titulo));
        $document->setValue('no_contrato_proyecto', $noContratoProyecto);
        $document->setValue('dia', $dia);
        $document->setValue('mes', strtolower($mes));
        $document->setValue('year', $year);
        $document->setValue('direccion', ucwords($direccion));
        $document->setValue('no_contrato', $noContrato);
        $document->setValue('cantidad_emisiones', $cantEmisiones);
        $document->setValue('formato', $formato);
        $document->setValue('duracion_pantalla', $duracion);
        $document->setValue('pre_grabacion', $etapaPreFilmacion);
        $document->setValue('grabacion', $etapaFilmacion);
        $document->setValue('post_grabacion', $etapaPostFilmacion);
        $document->setValue('categoria_proyecto', ucwords($categoria));
        $document->setValue('total_presupuesto', $totalPresupuesto);
        $document->setValue('total_presupuesto_letra', strtolower($totalPresupuestoLetra));
        $document->setValue('total_cargo', $totalCargo);
        $document->setValue('total_cargo_letra', strtolower($totalCargoLetra));
        $document->setValue('total_otros', $totalOtro);
        $document->setValue('total_otros_letra', strtolower($totalOtroLetra));
        $document->setValue('productor_general', ucwords($productor));
        $document->setValue('director_general', ucwords($director));

        $file = 'CttoNo. ' . $noContrato . ' ' . strtoupper($concepto) . ' ' . ucwords($nombreCompleto) . ' ' . ucwords($titulo) . ' ' . $year . '.docx';
        header('Content-Disposition: attachment; filename="' . $file . '"');
        $document->saveAs("php://output");

        exit;
    }

    public function exportarAsistenteAlAProduccion($dia, $mes, $proyecto)
    {
        $phpWord = new PhpWord();
        $document = $phpWord->loadTemplate('plantillas/ctroActor.docx');

        //Asignar valores
        $actual = $proyecto->getProyecto()->getFechaInicio();
        $date = $actual->format('Y');
        $nombreCompleto = $proyecto->getPersona()->nombreCompleto();
        $ci = $proyecto->getPersona()->getCarnetIdentidad();
        $ciudadania = $proyecto->getPersona()->getCiudadania()->getNombre();
        //Comprobar si tiene NIT
        $nit = '';
        if ($proyecto->getPersona()->getIsNIT()) {
            $nit = 'y NIT ' . $ci;
        }
        $titulo = $proyecto->getProyecto()->getTitulo();
        $noContratoProyecto = $proyecto->getProyecto()->getNoContratoCanal();
        $year = $date;
        $noContrato = $proyecto->getNoContrato();
        $direccion = $proyecto->getPersona()->getDireccion();
        $profesion = $proyecto->getPersona()->getProfesion()->getNombre();
        $concepto = $proyecto->getCargo()->getNombre();
        $funcion = $proyecto->getCargo()->getFuncion();
        $categoria = $proyecto->getProyecto()->getTipo()->getNombre();
        //Comprobar si tiene registro del creador
        $registroCreador = '';
        if ($proyecto->getPersona()->getNoRegistroCreador()) {
            $registroCreador = 'inscripto en el Registro del Creador con en número de carnet ' . $proyecto->getPersona()->getNoRegistroCreador() . ', ';
        }
        $cantEmisiones = $proyecto->getProyecto()->getEmisiones();
        $formato = $proyecto->getProyecto()->getFormato()->getNombre();
        $duracion = $proyecto->getProyecto()->getTiempoPantalla();
        $etapaPreFilmacion = $proyecto->getProyecto()->getEtapaPreFilmacion();
        $etapaFilmacion = $proyecto->getProyecto()->getEtapaFilmacion();
        $etapaPostFilmacion = $proyecto->getProyecto()->getEtapaPostFilmacion();
        $totalPresupuesto = $proyecto->getPresupuestoTotal();
        $totalPresupuestoLetra = $this->convertirNumeroLetra($totalPresupuesto);
        $totalCargo = $proyecto->getPresupuestoCargo();
        $totalCargoLetra = $this->convertirNumeroLetra($totalCargo);
        $totalOtro = $proyecto->getPresupuestoOtroIngreso();
        $totalOtroLetra = $this->convertirNumeroLetra($totalOtro);
        $valorPrograma = $proyecto->getValorPrograma();
        $valorProgramaLetra = $this->convertirNumeroLetra($valorPrograma);
        $productor = $proyecto->getProyecto()->getNombresProductorGeneral();
        $director = $proyecto->getProyecto()->getNombresDirectorGeneral();

        //Sustitucion de los keys q estan en la plantilla
        $document->setValue('nombre_persona', ucwords($nombreCompleto));
        $document->setValue('carnet_identidad', $ci);
        $document->setValue('ciudadania', strtolower($ciudadania));
        $document->setValue('profesion', strtoupper($profesion));
        $document->setValue('concepto', ucwords($concepto));
        $document->setValue('función_concepto', $funcion);
        $document->setValue('registro_creador', $registroCreador);
        $document->setValue('nit', $nit);
        $document->setValue('titulo', ucwords($titulo));
        $document->setValue('no_contrato_proyecto', $noContratoProyecto);
        $document->setValue('dia', $dia);
        $document->setValue('mes', strtolower($mes));
        $document->setValue('year', $year);
        $document->setValue('direccion', ucwords($direccion));
        $document->setValue('no_contrato', $noContrato);
        $document->setValue('cantidad_emisiones', $cantEmisiones);
        $document->setValue('formato', $formato);
        $document->setValue('duracion_pantalla', $duracion);
        $document->setValue('pre_grabacion', $etapaPreFilmacion);
        $document->setValue('grabacion', $etapaFilmacion);
        $document->setValue('post_grabacion', $etapaPostFilmacion);
        $document->setValue('categoria_proyecto', ucwords($categoria));
        $document->setValue('total_presupuesto', $totalPresupuesto);
        $document->setValue('total_presupuesto_letra', strtolower($totalPresupuestoLetra));
        $document->setValue('total_cargo', $totalCargo);
        $document->setValue('total_cargo_letra', strtolower($totalCargoLetra));
        $document->setValue('total_otros', $totalOtro);
        $document->setValue('total_otros_letra', strtolower($totalOtroLetra));
        $document->setValue('valor_programa', $valorPrograma);
        $document->setValue('valor_programa_letra', strtolower($valorProgramaLetra));
        $document->setValue('productor_general', ucwords($productor));
        $document->setValue('director_general', ucwords($director));

        $file = 'CttoNo. ' . $noContrato . ' ' . strtoupper($concepto) . ' ' . ucwords($nombreCompleto) . ' ' . ucwords($titulo) . ' ' . $year . '.docx';
        header('Content-Disposition: attachment; filename="' . $file . '"');
        $document->saveAs("php://output");

        exit;
    }

    public function exportarTCPTransportacion($dia, $mes, $proyecto)
    {
        $phpWord = new PhpWord();
        $document = $phpWord->loadTemplate('plantillas/ctroTCPChofer.docx');

        //Asignar valores
        $actual = $proyecto->getProyecto()->getFechaInicio();
        $date = $actual->format('Y');
        $nombreCompleto = $proyecto->getPersona()->nombreCompleto();
        $ci = $proyecto->getPersona()->getCarnetIdentidad();
        $ciudadania = $proyecto->getPersona()->getCiudadania()->getNombre();
        $noLicencia = $proyecto->getPersona()->getNoLicencia();
        $noLicenciaSanitaria = $proyecto->getPersona()->getNoLicenciaSanitaria();
        $tituloLicencia = $proyecto->getPersona()->getTituloLicencia();
        $banco = $proyecto->getPersona()->getBanco()->getNombre();
        $noCuentaCUP = $proyecto->getPersona()->getNoCuentaCUP();
        $noCuentaCUC = $proyecto->getPersona()->getNoCuentaCUC();
        $noSucursal = $proyecto->getPersona()->getNoSucursal();
        //Comprobar si tiene NIT
        $nit = '';
        if ($proyecto->getPersona()->getIsNIT()) {
            $nit = 'y NIT ' . $ci;
        }
        $titulo = $proyecto->getProyecto()->getTitulo();
        $noContratoProyecto = $proyecto->getProyecto()->getNoContratoCanal();
        $year = $date;
        $noContrato = $proyecto->getNoContrato();
        $direccion = $proyecto->getPersona()->getDireccion();
        $concepto = $proyecto->getCargo()->getNombre();
        $categoria = $proyecto->getProyecto()->getTipo()->getNombre();
        //Comprobar si tiene registro del creador
        $registroCreador = '';
        if ($proyecto->getPersona()->getNoRegistroCreador()) {
            $registroCreador = 'inscripto en el Registro del Creador con en número de carnet ' . $proyecto->getPersona()->getNoRegistroCreador() . ', ';
        }
        $cantEmisiones = $proyecto->getProyecto()->getEmisiones();
        $totalPresupuesto = $proyecto->getPresupuestoTotal();
        $totalPresupuestoLetra = $this->convertirNumeroLetra($totalPresupuesto);
        $productor = $proyecto->getProyecto()->getNombresProductorGeneral();
        $director = $proyecto->getProyecto()->getNombresDirectorGeneral();

        //Sustitucion de los keys q estan en la plantilla
        $document->setValue('nombre_persona', ucwords($nombreCompleto));
        $document->setValue('carnet_identidad', $ci);
        $document->setValue('ciudadania', strtolower($ciudadania));
        $document->setValue('concepto', ucwords($concepto));
        $document->setValue('registro_creador', $registroCreador);
        $document->setValue('nit', $nit);
        $document->setValue('titulo', ucwords($titulo));
        $document->setValue('no_contrato_proyecto', $noContratoProyecto);
        $document->setValue('dia', $dia);
        $document->setValue('mes', strtolower($mes));
        $document->setValue('year', $year);
        $document->setValue('direccion', ucwords($direccion));
        $document->setValue('no_contrato', $noContrato);
        $document->setValue('cantidad_emisiones', $cantEmisiones);
        $document->setValue('categoria_proyecto', ucwords($categoria));
        $document->setValue('total_presupuesto', $totalPresupuesto);
        $document->setValue('total_presupuesto_letra', strtolower($totalPresupuestoLetra));
        $document->setValue('licencia', $noLicencia);
        $document->setValue('licencia_sanitaria', $noLicenciaSanitaria);
        $document->setValue('titulo_licencia', $tituloLicencia);
        $document->setValue('cuenta_cup', $noCuentaCUP);
        $document->setValue('cuenta_cuc', $noCuentaCUC);
        $document->setValue('banco', $banco);
        $document->setValue('no_sucursal', $noSucursal);
        $document->setValue('productor_general', ucwords($productor));
        $document->setValue('director_general', ucwords($director));

        $file = 'TCP CttoNo. ' . $noContrato . ' ' . strtoupper($concepto) . ' ' . ucwords($nombreCompleto) . ' ' . ucwords($titulo) . ' ' . $year . '.docx';
        header('Content-Disposition: attachment; filename="' . $file . '"');
        $document->saveAs("php://output");

        exit;
    }

    public function exportarGeneral($dia, $mes, $proyecto)
    {
        $phpWord = new PhpWord();
        $document = $phpWord->loadTemplate('plantillas/ctroGeneral.docx');

        //Asignar valores
        $actual = $proyecto->getProyecto()->getFechaInicio();
        $date = $actual->format('Y');
        $nombreCompleto = $proyecto->getPersona()->nombreCompleto();
        $ci = $proyecto->getPersona()->getCarnetIdentidad();
        $ciudadania = $proyecto->getPersona()->getCiudadania()->getNombre();
        //Comprobar si tiene NIT
        $nit = '';
        if ($proyecto->getPersona()->getIsNIT()) {
            $nit = 'y NIT ' . $ci;
        }
        $titulo = $proyecto->getProyecto()->getTitulo();
        $noContratoProyecto = $proyecto->getProyecto()->getNoContratoCanal();
        $year = $date;
        $noContrato = $proyecto->getNoContrato();
        $direccion = $proyecto->getPersona()->getDireccion();
        $profesion = $proyecto->getPersona()->getProfesion()->getNombre();
        $concepto = $proyecto->getCargo()->getNombre();
        $funcion = $proyecto->getCargo()->getFuncion();
        $categoria = $proyecto->getProyecto()->getTipo()->getNombre();
        //Comprobar si tiene registro del creador
        $registroCreador = '';
        if ($proyecto->getPersona()->getNoRegistroCreador()) {
            $registroCreador = 'inscripto en el Registro del Creador con en número de carnet ' . $proyecto->getPersona()->getNoRegistroCreador() . ', ';
        }
        $cantEmisiones = $proyecto->getProyecto()->getEmisiones();
        $formato = $proyecto->getProyecto()->getFormato()->getNombre();
        $duracion = $proyecto->getProyecto()->getTiempoPantalla();
        $etapaPreFilmacion = $proyecto->getProyecto()->getEtapaPreFilmacion();
        $etapaFilmacion = $proyecto->getProyecto()->getEtapaFilmacion();
        $etapaPostFilmacion = $proyecto->getProyecto()->getEtapaPostFilmacion();
        $totalPresupuesto = $proyecto->getPresupuestoTotal();
        $totalPresupuestoLetra = $this->convertirNumeroLetra($totalPresupuesto);
        $totalCargo = $proyecto->getPresupuestoCargo();
        $totalCargoLetra = $this->convertirNumeroLetra($totalCargo);
        $totalOtro = $proyecto->getPresupuestoOtroIngreso();
        $totalOtroLetra = $this->convertirNumeroLetra($totalOtro);
        $productor = $proyecto->getProyecto()->getNombresProductorGeneral();
        $director = $proyecto->getProyecto()->getNombresDirectorGeneral();

        //Sustitucion de los keys q estan en la plantilla
        $document->setValue('nombre_persona', ucwords($nombreCompleto));
        $document->setValue('carnet_identidad', $ci);
        $document->setValue('ciudadania', strtolower($ciudadania));
        $document->setValue('profesion', ucwords($profesion));
        $document->setValue('concepto', ucwords($concepto));
        $document->setValue('función_concepto', $funcion);
        $document->setValue('registro_creador', $registroCreador);
        $document->setValue('nit', $nit);
        $document->setValue('titulo', ucwords($titulo));
        $document->setValue('no_contrato_proyecto', $noContratoProyecto);
        $document->setValue('dia', $dia);
        $document->setValue('mes', strtolower($mes));
        $document->setValue('year', $year);
        $document->setValue('direccion', ucwords($direccion));
        $document->setValue('no_contrato', $noContrato);
        $document->setValue('cantidad_emisiones', $cantEmisiones);
        $document->setValue('formato', $formato);
        $document->setValue('duracion_pantalla', $duracion);
        $document->setValue('pre_grabacion', $etapaPreFilmacion);
        $document->setValue('grabacion', $etapaFilmacion);
        $document->setValue('post_grabacion', $etapaPostFilmacion);
        $document->setValue('categoria_proyecto', ucwords($categoria));
        $document->setValue('total_presupuesto', $totalPresupuesto);
        $document->setValue('total_presupuesto_letra', strtolower($totalPresupuestoLetra));
        $document->setValue('total_cargo', $totalCargo);
        $document->setValue('total_cargo_letra', strtolower($totalCargoLetra));
        $document->setValue('total_otros', $totalOtro);
        $document->setValue('total_otros_letra', strtolower($totalOtroLetra));
        $document->setValue('productor_general', ucwords($productor));
        $document->setValue('director_general', ucwords($director));

        $file = 'CttoNo. ' . $noContrato . ' ' . strtoupper($concepto) . ' ' . ucwords($nombreCompleto) . ' ' . ucwords($titulo) . ' ' . $year . '.docx';
        header('Content-Disposition: attachment; filename="' . $file . '"');
        $document->saveAs("php://output");

        exit;
    }

    public function exportarConceptos($dia, $mes, $proyecto, $totalPresupuesto, $conceptos)
    {
        $phpWord = new PhpWord();
        $document = $phpWord->loadTemplate('plantillas/ctroConceptos.docx');

        //Asignar valores
        $actual = $proyecto->getProyecto()->getFechaInicio();
        $date = $actual->format('Y');
        $nombreCompleto = $proyecto->getPersona()->nombreCompleto();
        $ci = $proyecto->getPersona()->getCarnetIdentidad();
        $ciudadania = $proyecto->getPersona()->getCiudadania()->getNombre();
        //Comprobar si tiene NIT
        $nit = '';
        if ($proyecto->getPersona()->getIsNIT()) {
            $nit = 'y NIT ' . $ci;
        }
        $titulo = $proyecto->getProyecto()->getTitulo();
        $noContratoProyecto = $proyecto->getProyecto()->getNoContratoCanal();
        $year = $date;
        $noContrato = $proyecto->getNoContrato();
        $direccion = $proyecto->getPersona()->getDireccion();
        $profesion = '';
        if ($proyecto->getPersona()->getProfesion()) {
            $profesion = $proyecto->getPersona()->getProfesion()->getNombre();
        }
        $categoria = $proyecto->getProyecto()->getTipo()->getNombre();
        //Comprobar si tiene registro del creador
        $registroCreador = '';
        if ($proyecto->getPersona()->getNoRegistroCreador()) {
            $registroCreador = 'inscripto en el Registro del Creador con en número de carnet ' . $proyecto->getPersona()->getNoRegistroCreador() . ', ';
        }
        $cantEmisiones = $proyecto->getProyecto()->getEmisiones();
        $formato = $proyecto->getProyecto()->getFormato()->getNombre();
        $duracion = $proyecto->getProyecto()->getTiempoPantalla();
        $etapaPreFilmacion = $proyecto->getProyecto()->getEtapaPreFilmacion();
        $etapaFilmacion = $proyecto->getProyecto()->getEtapaFilmacion();
        $etapaPostFilmacion = $proyecto->getProyecto()->getEtapaPostFilmacion();
        $totalPresupuestoLetra = $this->convertirNumeroLetra($totalPresupuesto);
        $totalCargo = $proyecto->getPresupuestoCargo();
        $totalCargoLetra = $this->convertirNumeroLetra($totalCargo);
        $totalOtro = $proyecto->getPresupuestoOtroIngreso();
        $totalOtroLetra = $this->convertirNumeroLetra($totalOtro);
        $director = $proyecto->getProyecto()->getNombresDirectorGeneral();
        $productor = $proyecto->getProyecto()->getNombresProductorGeneral();

        $cantConceptos = count($conceptos);
        $cargos = '';
        $montosCargos = '';
        $i = 1;
        foreach ($conceptos as $persona) {
            if ($i === 1) {
                $cargos .= " " . $persona['cargo'];
                $montosCargos .= " $ " . $persona['presupuestoCargo'] . " CUC como " . strtoupper($persona['cargo']);
                $i++;
            } else {
                if ($i === $cantConceptos ) {
                    $cargos .= " y " . $persona['cargo'];
                    $montosCargos .= " y $ " . $persona['presupuestoCargo'] . " CUC como " . strtoupper($persona['cargo']);
                } else {
                    $cargos .= ", " . $persona['cargo'];
                    $montosCargos .= ", $ " . $persona['presupuestoCargo'] . " CUC como " . strtoupper($persona['cargo']);
                }
                $i++;
            }
        }

        //Sustitucion de los keys q estan en la plantilla
        $document->setValue('nombre_persona', ucwords($nombreCompleto));
        $document->setValue('carnet_identidad', $ci);
        $document->setValue('ciudadania', strtolower($ciudadania));
        $document->setValue('profesion', ucwords($profesion));
        $document->setValue('registro_creador', $registroCreador);
        $document->setValue('nit', $nit);
        $document->setValue('titulo', ucwords($titulo));
        $document->setValue('no_contrato_proyecto', $noContratoProyecto);
        $document->setValue('dia', $dia);
        $document->setValue('mes', strtolower($mes));
        $document->setValue('year', $year);
        $document->setValue('direccion', ucwords($direccion));
        $document->setValue('no_contrato', $noContrato);
        $document->setValue('cantidad_emisiones', $cantEmisiones);
        $document->setValue('formato', $formato);
        $document->setValue('duracion_pantalla', $duracion);
        $document->setValue('pre_grabacion', $etapaPreFilmacion);
        $document->setValue('grabacion', $etapaFilmacion);
        $document->setValue('post_grabacion', $etapaPostFilmacion);
        $document->setValue('categoria_proyecto', ucwords($categoria));
        $document->setValue('total_presupuesto', $totalPresupuesto);
        $document->setValue('total_presupuesto_letra', strtolower($totalPresupuestoLetra));
        $document->setValue('total_cargo', $totalCargo);
        $document->setValue('total_cargo_letra', strtolower($totalCargoLetra));
        $document->setValue('total_otros', $totalOtro);
        $document->setValue('total_otros_letra', strtolower($totalOtroLetra));
        $document->setValue('director_general', ucwords($director));
        $document->setValue('productor_general', ucwords($productor));
        $document->setValue('conceptos', strtoupper($cargos));
        $document->setValue('total_conceptos', $montosCargos);

        $file = ' CONCEPTOS ' . ucwords($nombreCompleto) . ' ' . ucwords($titulo) . ' ' . $year . '.docx';
        header('Content-Disposition: attachment; filename="' . $file . '"');
        $document->saveAs("php://output");

        exit;
    }

    public function exportarTCPAlimentacion($dia, $mes, $proyecto)
    {
        $phpWord = new PhpWord();
        $document = $phpWord->loadTemplate('plantillas/ctroTCPAlimentacion.docx');

        //Asignar valores
        $actual = $proyecto->getProyecto()->getFechaInicio();
        $date = $actual->format('Y');
        $nombreCompleto = $proyecto->getPersona()->nombreCompleto();
        $ci = $proyecto->getPersona()->getCarnetIdentidad();
        $ciudadania = $proyecto->getPersona()->getCiudadania()->getNombre();
        $noLicencia = $proyecto->getPersona()->getNoLicencia();
        $noLicenciaSanitaria = $proyecto->getPersona()->getNoLicenciaSanitaria();
        $tituloLicencia = $proyecto->getPersona()->getTituloLicencia();
        $banco = $proyecto->getPersona()->getBanco()->getNombre();
        $noCuentaCUP = $proyecto->getPersona()->getNoCuentaCUP();
        $noCuentaCUC = $proyecto->getPersona()->getNoCuentaCUC();
        $noSucursal = $proyecto->getPersona()->getNoSucursal();
        //Comprobar si tiene NIT
        $nit = '';
        if ($proyecto->getPersona()->getIsNIT()) {
            $nit = 'y NIT ' . $ci;
        }
        $titulo = $proyecto->getProyecto()->getTitulo();
        $noContratoProyecto = $proyecto->getProyecto()->getNoContratoCanal();
        $year = $date;
        $noContrato = $proyecto->getNoContrato();
        $direccion = $proyecto->getPersona()->getDireccion();
        $concepto = $proyecto->getCargo()->getNombre();
        $funcion = $proyecto->getCargo()->getFuncion();
        $categoria = $proyecto->getProyecto()->getTipo()->getNombre();
        //Comprobar si tiene registro del creador
        $registroCreador = '';
        if ($proyecto->getPersona()->getNoRegistroCreador()) {
            $registroCreador = 'inscripto en el Registro del Creador con en número de carnet ' . $proyecto->getPersona()->getNoRegistroCreador() . ', ';
        }
        $cantEmisiones = $proyecto->getProyecto()->getEmisiones();
        $formato = $proyecto->getProyecto()->getFormato()->getNombre();
        $duracion = $proyecto->getProyecto()->getTiempoPantalla();
        $etapaPreFilmacion = $proyecto->getProyecto()->getEtapaPreFilmacion();
        $etapaFilmacion = $proyecto->getProyecto()->getEtapaFilmacion();
        $etapaPostFilmacion = $proyecto->getProyecto()->getEtapaPostFilmacion();
        $totalPresupuesto = $proyecto->getPresupuestoTotal();
        $totalPresupuestoLetra = $this->convertirNumeroLetra($totalPresupuesto);
        $totalCargo = $proyecto->getPresupuestoCargo();
        $totalCargoLetra = $this->convertirNumeroLetra($totalCargo);
        $totalOtro = $proyecto->getPresupuestoOtroIngreso();
        $totalOtroLetra = $this->convertirNumeroLetra($totalOtro);
        $productor = $proyecto->getProyecto()->getNombresProductorGeneral();
        $director = $proyecto->getProyecto()->getNombresDirectorGeneral();

        //Sustitucion de los keys q estan en la plantilla
        $document->setValue('nombre_persona', ucwords($nombreCompleto));
        $document->setValue('carnet_identidad', $ci);
        $document->setValue('ciudadania', strtolower($ciudadania));
        $document->setValue('concepto', ucwords($concepto));
        $document->setValue('función_concepto', $funcion);
        $document->setValue('registro_creador', $registroCreador);
        $document->setValue('nit', $nit);
        $document->setValue('titulo', ucwords($titulo));
        $document->setValue('no_contrato_proyecto', $noContratoProyecto);
        $document->setValue('dia', $dia);
        $document->setValue('mes', strtolower($mes));
        $document->setValue('year', $year);
        $document->setValue('direccion', ucwords($direccion));
        $document->setValue('no_contrato', $noContrato);
        $document->setValue('cantidad_emisiones', $cantEmisiones);
        $document->setValue('formato', $formato);
        $document->setValue('duracion_pantalla', $duracion);
        $document->setValue('pre_grabacion', $etapaPreFilmacion);
        $document->setValue('grabacion', $etapaFilmacion);
        $document->setValue('post_grabacion', $etapaPostFilmacion);
        $document->setValue('categoria_proyecto', ucwords($categoria));
        $document->setValue('total_presupuesto', $totalPresupuesto);
        $document->setValue('total_presupuesto_letra', strtolower($totalPresupuestoLetra));
        $document->setValue('total_cargo', $totalCargo);
        $document->setValue('total_cargo_letra', strtolower($totalCargoLetra));
        $document->setValue('total_otros', $totalOtro);
        $document->setValue('total_otros_letra', strtolower($totalOtroLetra));
        $document->setValue('licencia', $noLicencia);
        $document->setValue('licencia_sanitaria', $noLicenciaSanitaria);
        $document->setValue('titulo_licencia', $tituloLicencia);
        $document->setValue('cuenta_cup', $noCuentaCUP);
        $document->setValue('cuenta_cuc', $noCuentaCUC);
        $document->setValue('banco', $banco);
        $document->setValue('no_sucursal', $noSucursal);
        $document->setValue('productor_general', ucwords($productor));
        $document->setValue('director_general', ucwords($director));

        $file = 'CttoNo. ' . $noContrato . ' ' . strtoupper($concepto) . ' ' . ucwords($nombreCompleto) . ' ' . ucwords($titulo) . ' ' . $year . '.docx';
        header('Content-Disposition: attachment; filename="' . $file . '"');
        $document->saveAs("php://output");

        exit;
    }

    public function exportarSuplemento($dia, $mes, $personaReporte)
    {
        $phpWord = new PhpWord();
        $document = $phpWord->loadTemplate('plantillas/suplePago.docx');

        //Asignar valores
        $actual = $personaReporte->getReporte()->getProyecto()->getFechaInicio();
        $noSuplemento = $personaReporte->getNoSuplemento();
        $date = $actual->format('Y');
        $nombreCompleto = $personaReporte->getProyectoPersonaCargo()->getPersona()->nombreCompleto();
        $ci = $personaReporte->getProyectoPersonaCargo()->getPersona()->getCarnetIdentidad();
        $ciudadania = $personaReporte->getProyectoPersonaCargo()->getPersona()->getCiudadania()->getNombre();
        //Comprobar si tiene NIT
        $nit = '';
        if ($personaReporte->getProyectoPersonaCargo()->getPersona()->getIsNIT()) {
            $nit = 'y NIT ' . $ci;
        }
        $titulo = $personaReporte->getReporte()->getProyecto()->getTitulo();
        $noContratoProyecto = $personaReporte->getReporte()->getProyecto()->getNoContratoCanal();
        $year = $date;
        $noContrato = $personaReporte->getProyectoPersonaCargo()->getNoContrato();
        $direccion = $personaReporte->getProyectoPersonaCargo()->getPersona()->getDireccion();
        $profesion = $personaReporte->getProyectoPersonaCargo()->getPersona()->getProfesion()->getNombre();
        $categoria = $personaReporte->getReporte()->getProyecto()->getTipo()->getNombre();
        //Comprobar si tiene registro del creador
        $registroCreador = '';
        if ($personaReporte->getProyectoPersonaCargo()->getPersona()->getNoRegistroCreador()) {
            $registroCreador = 'inscripto en el Registro del Creador con en número de carnet ' . $personaReporte->getProyectoPersonaCargo()->getPersona()->getNoRegistroCreador() . ', ';
        }
        $cantEmisiones = $personaReporte->getReporte()->getProyecto()->getEmisiones();
        $totalPresupuesto = $personaReporte->getProyectoPersonaCargo()->getPresupuestoTotal();
        $totalPresupuestoLetra = $this->convertirNumeroLetra($totalPresupuesto);
        $totalCargo = $personaReporte->getProyectoPersonaCargo()->getPresupuestoCargo();
        $totalCargoLetra = $this->convertirNumeroLetra($totalCargo);
        $totalOtro = $personaReporte->getProyectoPersonaCargo()->getPresupuestoOtroIngreso();
        $totalOtroLetra = $this->convertirNumeroLetra($totalOtro);
        $productor = $personaReporte->getReporte()->getProyecto()->getNombresProductorGeneral();

        //Sustitucion de los keys q estan en la plantilla
        $document->setValue('nombre_persona', ucwords($nombreCompleto));
        $document->setValue('carnet_identidad', $ci);
        $document->setValue('ciudadania', strtolower($ciudadania));
        $document->setValue('profesion', strtoupper($profesion));
        $document->setValue('registro_creador', $registroCreador);
        $document->setValue('nit', $nit);
        $document->setValue('titulo', strtoupper($titulo));
        $document->setValue('no_contrato_proyecto', $noContratoProyecto);
        $document->setValue('dia', $dia);
        $document->setValue('mes', strtolower($mes));
        $document->setValue('year', $year);
        $document->setValue('direccion', ucwords($direccion));
        $document->setValue('no_contrato', $noContrato);
        $document->setValue('no_suplemento', $noSuplemento);
        $document->setValue('cantidad_emisiones', $cantEmisiones);
        $document->setValue('categoria_proyecto', ucwords($categoria));
        $document->setValue('total_presupuesto', $totalPresupuesto);
        $document->setValue('total_presupuesto_letra', strtolower($totalPresupuestoLetra));
        $document->setValue('total_cargo', $totalCargo);
        $document->setValue('total_cargo_letra', strtolower($totalCargoLetra));
        $document->setValue('total_otros', $totalOtro);
        $document->setValue('total_otros_letra', strtolower($totalOtroLetra));
        $document->setValue('productor_general', ucwords($productor));

        $file = 'Suplemento. ' . $noSuplemento . ' Ctto ' . $noContrato . ' ' . ucwords($nombreCompleto) . ' ' . ucwords($titulo) . ' ' . $year . '.docx';
        header('Content-Disposition: attachment; filename="' . $file . '"');
        $document->saveAs("php://output");

        exit;
    }

    public function exportarEjemplo1()
    {

        $phpWord = new PhpWord();
        $properties = $phpWord->getDocInfo();
        $properties->setCreator('Alain');
        $properties->setCompany('RTV Comercial');
        $properties->setTitle('Contratación Director General');
        $properties->setDescription('My description');
        $properties->setCategory('Contratos');
        $properties->setLastModifiedBy('Alain');
        $properties->setCreated(mktime(0, 0, 0, 10, 28, 2014));
        $properties->setModified(mktime(0, 0, 0, 10, 28, 2020));
        $properties->setSubject('My subject');
        $properties->setKeywords('contrato, director general, word');

        $paper = new Paper();
        $paper->setSize('A4');

        $section = $phpWord->createSection();

        //por defecto para todo el documento
        $phpWord->setDefaultFontName('Arial');
        $phpWord->setDefaultFontSize(12);

        //Logo
        $imageStyle = array(
            'width' => 40,
            'height' => 40,
            'wrappingStyle' => 'square',
            'positioning' => 'absolute',
            'posHorizontalRel' => 'margin',
            'posVerticalRel' => 'line',
        );
        $section->addImage('images/logoRTV.png', $imageStyle);

        // Adding Text element with font customized using named font style...
        $fontStyleName = 'oneUserDefinedStyle';
        $phpWord->addFontStyle(
            $fontStyleName,
            array('name' => 'Tahoma', 'size' => 14, 'color' => '1B2232', 'bold' => false)
        );

        $section->addText('DE UNA PARTE:', ['bold' => true]);
        $section->addText('La Empresa Comercializadora de la Radio y la Televisión, en forma abreviada RTV Comercial,
         cuya creación fue autorizada mediante Resolución No. 500 de fecha 26 de octubre de 2006, 
         dictada por el Ministro de Economía y Planificación, constituida mediante Resolución No. 3 de fecha 26 de enero de 2007, 
         dictada por el Presidente del Instituto Cubano de Radio y Televisión, con domicilio legal en calle 17 esquina M Edificio FOCSA apto. 4M, 
         Vedado, Plaza de la Revolución, teléfonos 78319456, cuenta bancaria en moneda libremente convertible estandarizada No. 0407610002180028, 
         en el Banco Internacional de Comercio BICSA Sucursal 076, y con cuenta bancaria en moneda nacional No 0526420029280017 en el Banco Metropolitano Sucursal 264, 
         inscripto con el código REEUP 233-0-4173, con Número de Identificación Tributaria 01004105145, 
         representada en este acto por Yaima González Piñeiro   en su carácter de Directora de Servicios de Producción, 
         lo cual acredita mediante la Resolución 6 de fecha 1 de febrero de 2018, quien a los efectos del presente contrato y en lo adelante se denominará RTVC.',
            $fontStyleName);


        // Adding Text element with font customized using explicitly created font style object...
        $fontStyle = new \PhpOffice\PhpWord\Style\Font();
        $fontStyle->setBold(true);
        $fontStyle->setName('Tahoma');
        $fontStyle->setSize(13);
        $myTextElement = $section->addText('"Believe you can and you\'re halfway there." (Theodor Roosevelt)');
        $myTextElement->setFontStyle($fontStyle);

        $fontStyle = new \PhpOffice\PhpWord\Style\Font();
        $fontStyle->setBold(true);
        $fontStyle->setName('Arial');
        $fontStyle->setSize(16);
        $myTextElement = $section->addText('Hello World');
        $myTextElement->setFontStyle($fontStyle);

        $file = 'HelloWorld.docx';
        header("Content-Description: File Transfer");
        header('Content-Disposition: attachment; filename="' . $file . '"');
        header('Content-Type: application/vnd.openxmlformats-officedocument.wordprocessingml.document');
        header('Content-Transfer-Encoding: binary');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Expires: 0');
        $xmlWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'Word2007');
        $xmlWriter->save("php://output");

        exit;
    }

    public function exportarEjemplo2()
    {

        $phpWord = new PhpWord();
        $properties = $phpWord->getDocInfo();
        $properties->setCreator('Alain');
        $properties->setCompany('RTV Comercial');
        $properties->setTitle('Contratación Director General');
        $properties->setDescription('My description');
        $properties->setCategory('Contratos');
        $properties->setLastModifiedBy('Alain');
        $properties->setCreated(mktime(0, 0, 0, 10, 28, 2014));
        $properties->setModified(mktime(0, 0, 0, 10, 28, 2020));
        $properties->setSubject('My subject');
        $properties->setKeywords('contrato, director general, word');

        $section = $phpWord->createSection();

        $header = $section->addHeader();

        //Logo
        $imageStyle = array(
            'width' => 110,
            'height' => 60,
        );

        $header->addImage('images/logContrato.png', $imageStyle);


        //por defecto para todo el documento
        $phpWord->setDefaultFontName('Arial');
        $phpWord->setDefaultFontSize(10);

        $fontStyleTitulo = new \PhpOffice\PhpWord\Style\Font();
        $fontStyleTitulo->setBold(true);

        $fontStyleName = 'oneUserDefinedStyle';
        $phpWord->addFontStyle(
            $fontStyleName,
            array(
                'align' => 'center',
                'bold' => true)
        );

        //Titulo
        $section->addText('CONTRATO DE OBRA POR ENCARGO', $fontStyleName);
        $section->addText('“NATURALMENTE/2020¨/ RTVC / SERVI / P- 35 /2020 No Ctto. 1', $fontStyleName);

        $section->addTextBreak(1);

        //1er Párrafo
        $section->addText('DE UNA PARTE: La Empresa Comercializadora de la Radio y la Televisión, en forma abreviada RTV Comercial, 
        cuya creación fue autorizada mediante Resolución No. 500 de fecha 26 de octubre de 2006, dictada por el Ministro de Economía y Planificación, 
        constituida mediante Resolución No. 3 de fecha 26 de enero de 2007, dictada por el Presidente del Instituto Cubano de Radio y Televisión, 
        con domicilio legal en calle 17 esquina M Edificio FOCSA apto. 4M, Vedado, Plaza de la Revolución, teléfonos 78319456, cuenta bancaria en 
        moneda libremente convertible estandarizada No. 0407610002180028, en el Banco Internacional de Comercio BICSA Sucursal 076, 
        y con cuenta bancaria en moneda nacional No 0526420029280017 en el Banco Metropolitano Sucursal 264, inscripto con el código REEUP 233-0-4173, 
        con Número de Identificación Tributaria 01004105145, representada en este acto por Yaima González Piñeiro   en su carácter de Directora de Servicios de Producción, 
        lo cual acredita mediante la Resolución 6 de fecha 1 de febrero de 2018, quien a los efectos del presente contrato y en lo adelante se denominará RTVC.');

        $file = 'HelloWorld.docx';
        header("Content-Description: File Transfer");
        header('Content-Disposition: attachment; filename="' . $file . '"');
        header('Content-Type: application/vnd.openxmlformats-officedocument.wordprocessingml.document');
        header('Content-Transfer-Encoding: binary');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Expires: 0');
        $xmlWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'Word2007');
        $xmlWriter->save("php://output");

        exit;
    }

    function convertirNumeroLetra($numero)
    {
        $numf = $this->milmillon($numero);
        return $numf;
    }

    //MilMillon
    function milmillon($nummierod)
    {
        if ($nummierod >= 1000000000 && $nummierod < 2000000000) {
            $num_letrammd = "MIL " . ($this->cienmillon($nummierod % 1000000000));
        }
        if ($nummierod >= 2000000000 && $nummierod < 10000000000) {
            $num_letrammd = $this->unidad(Floor($nummierod / 1000000000)) . " MIL " . ($this->cienmillon($nummierod % 1000000000));
        }
        if ($nummierod < 1000000000)
            $num_letrammd = $this->cienmillon($nummierod);

        return $num_letrammd;
    }

    //CienMillon
    function cienmillon($numcmeros)
    {
        if ($numcmeros == 100000000)
            $num_letracms = "CIEN MILLONES";
        if ($numcmeros >= 100000000 && $numcmeros < 1000000000) {
            $num_letracms = $this->centena(Floor($numcmeros / 1000000)) . " MILLONES " . ($this->millon($numcmeros % 1000000));
        }
        if ($numcmeros < 100000000)
            $num_letracms = $this->decmillon($numcmeros);
        return $num_letracms;
    }

    //DecMillon
    function decmillon($numerodm)
    {
        if ($numerodm == 10000000)
            $num_letradmm = "DIEZ MILLONES";
        if ($numerodm > 10000000 && $numerodm < 20000000) {
            $num_letradmm = $this->decena(Floor($numerodm / 1000000)) . "MILLONES " . ($this->cienmiles($numerodm % 1000000));
        }
        if ($numerodm >= 20000000 && $numerodm < 100000000) {
            $num_letradmm = $this->decena(Floor($numerodm / 1000000)) . " MILLONES " . ($this->millon($numerodm % 1000000));
        }
        if ($numerodm < 10000000)
            $num_letradmm = $this->millon($numerodm);

        return $num_letradmm;
    }

    //Millon
    function millon($nummiero)
    {
        if ($nummiero >= 1000000 && $nummiero < 2000000) {
            $num_letramm = "UN MILLON " . ($this->cienmiles($nummiero % 1000000));
        }
        if ($nummiero >= 2000000 && $nummiero < 10000000) {
            $num_letramm = $this->unidad(Floor($nummiero / 1000000)) . " MILLONES " . ($this->cienmiles($nummiero % 1000000));
        }
        if ($nummiero < 1000000)
            $num_letramm = $this->cienmiles($nummiero);

        return $num_letramm;
    }

    //CienMiles
    function cienmiles($numcmero)
    {
        if ($numcmero == 100000)
            $num_letracm = "CIEN MIL";
        if ($numcmero >= 100000 && $numcmero < 1000000) {
            $num_letracm = $this->centena(Floor($numcmero / 1000)) . " MIL " . ($this->centena($numcmero % 1000));
        }
        if ($numcmero < 100000)
            $num_letracm = $this->decmiles($numcmero);
        return $num_letracm;
    }

    //Decmiles
    function decmiles($numdmero)
    {
        if ($numdmero == 10000)
            $numde = "DIEZ MIL";
        if ($numdmero > 10000 && $numdmero < 20000) {
            $numde = $this->decena(Floor($numdmero / 1000)) . "MIL " . ($this->centena($numdmero % 1000));
        }
        if ($numdmero >= 20000 && $numdmero < 100000) {
            $numde = $this->decena(Floor($numdmero / 1000)) . " MIL " . ($this->miles($numdmero % 1000));
        }
        if ($numdmero < 10000)
            $numde = $this->miles($numdmero);

        return $numde;
    }

    //Miles
    function miles($nummero)
    {
        if ($nummero >= 1000 && $nummero < 2000) {
            $numm = "MIL " . ($this->centena($nummero % 1000));
        }
        if ($nummero >= 2000 && $nummero < 10000) {
            $numm = $this->unidad(Floor($nummero / 1000)) . " MIL " . ($this->centena($nummero % 1000));
        }
        if ($nummero < 1000)
            $numm = $this->centena($nummero);

        return $numm;
    }

    //Centena
    function centena($numc)
    {
        if ($numc >= 100) {
            if ($numc >= 900 && $numc <= 999) {
                $numce = "NOVECIENTOS ";
                if ($numc > 900)
                    $numce = $numce . ($this->decena($numc - 900));
            } else if ($numc >= 800 && $numc <= 899) {
                $numce = "OCHOCIENTOS ";
                if ($numc > 800)
                    $numce = $numce . ($this->decena($numc - 800));
            } else if ($numc >= 700 && $numc <= 799) {
                $numce = "SETECIENTOS ";
                if ($numc > 700)
                    $numce = $numce . ($this->decena($numc - 700));
            } else if ($numc >= 600 && $numc <= 699) {
                $numce = "SEISCIENTOS ";
                if ($numc > 600)
                    $numce = $numce . ($this->decena($numc - 600));
            } else if ($numc >= 500 && $numc <= 599) {
                $numce = "QUINIENTOS ";
                if ($numc > 500)
                    $numce = $numce . ($this->decena($numc - 500));
            } else if ($numc >= 400 && $numc <= 499) {
                $numce = "CUATROCIENTOS ";
                if ($numc > 400)
                    $numce = $numce . ($this->decena($numc - 400));
            } else if ($numc >= 300 && $numc <= 399) {
                $numce = "TRESCIENTOS ";
                if ($numc > 300)
                    $numce = $numce . ($this->decena($numc - 300));
            } else if ($numc >= 200 && $numc <= 299) {
                $numce = "DOSCIENTOS ";
                if ($numc > 200)
                    $numce = $numce . ($this->decena($numc - 200));
            } else if ($numc >= 100 && $numc <= 199) {
                if ($numc == 100)
                    $numce = "CIEN ";
                else
                    $numce = "CIENTO " . ($this->decena($numc - 100));
            }
        } else
            $numce = $this->decena($numc);

        return $numce;
    }

    //Decena
    function decena($numdero)
    {

        if ($numdero >= 90 && $numdero <= 99) {
            $numd = "NOVENTA ";
            if ($numdero > 90)
                $numd = $numd . "Y " . ($this->unidad($numdero - 90));
        } else if ($numdero >= 80 && $numdero <= 89) {
            $numd = "OCHENTA ";
            if ($numdero > 80)
                $numd = $numd . "Y " . ($this->unidad($numdero - 80));
        } else if ($numdero >= 70 && $numdero <= 79) {
            $numd = "SETENTA ";
            if ($numdero > 70)
                $numd = $numd . "Y " . ($this->unidad($numdero - 70));
        } else if ($numdero >= 60 && $numdero <= 69) {
            $numd = "SESENTA ";
            if ($numdero > 60)
                $numd = $numd . "Y " . ($this->unidad($numdero - 60));
        } else if ($numdero >= 50 && $numdero <= 59) {
            $numd = "CINCUENTA ";
            if ($numdero > 50)
                $numd = $numd . "Y " . ($this->unidad($numdero - 50));
        } else if ($numdero >= 40 && $numdero <= 49) {
            $numd = "CUARENTA ";
            if ($numdero > 40)
                $numd = $numd . "Y " . ($this->unidad($numdero - 40));
        } else if ($numdero >= 30 && $numdero <= 39) {
            $numd = "TREINTA ";
            if ($numdero > 30)
                $numd = $numd . "Y " . ($this->unidad($numdero - 30));
        } else if ($numdero >= 20 && $numdero <= 29) {
            if ($numdero == 20)
                $numd = "VEINTE ";
            else
                $numd = "VEINTI" . ($this->unidad($numdero - 20));
        } else if ($numdero >= 10 && $numdero <= 19) {
            switch ($numdero) {
                case 10:
                {
                    $numd = "DIEZ ";
                    break;
                }
                case 11:
                {
                    $numd = "ONCE ";
                    break;
                }
                case 12:
                {
                    $numd = "DOCE ";
                    break;
                }
                case 13:
                {
                    $numd = "TRECE ";
                    break;
                }
                case 14:
                {
                    $numd = "CATORCE ";
                    break;
                }
                case 15:
                {
                    $numd = "QUINCE ";
                    break;
                }
                case 16:
                {
                    $numd = "DIECISEIS ";
                    break;
                }
                case 17:
                {
                    $numd = "DIECISIETE ";
                    break;
                }
                case 18:
                {
                    $numd = "DIECIOCHO ";
                    break;
                }
                case 19:
                {
                    $numd = "DIECINUEVE ";
                    break;
                }
            }
        } else
            $numd = $this->unidad($numdero);
        return $numd;
    }

    //Unidad
    function unidad($numuero)
    {
        switch ($numuero) {
            case 9:
            {
                $numu = "NUEVE";
                break;
            }
            case 8:
            {
                $numu = "OCHO";
                break;
            }
            case 7:
            {
                $numu = "SIETE";
                break;
            }
            case 6:
            {
                $numu = "SEIS";
                break;
            }
            case 5:
            {
                $numu = "CINCO";
                break;
            }
            case 4:
            {
                $numu = "CUATRO";
                break;
            }
            case 3:
            {
                $numu = "TRES";
                break;
            }
            case 2:
            {
                $numu = "DOS";
                break;
            }
            case 1:
            {
                $numu = "UNO";
                break;
            }
            case 0:
            {
                $numu = "";
                break;
            }
        }
        return $numu;
    }

    function genero($cadena){
        $resultado ='';
        if ((substr($cadena,10,1)%2) === 0 ) {
            $resultado = 'ciudadano';
        }else {
            $resultado = 'ciudadana';
        }
        return $resultado;
    }

}