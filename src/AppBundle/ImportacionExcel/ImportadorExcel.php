<?php


namespace AppBundle\ImportacionExcel;

use Doctrine\Common\Collections\ArrayCollection;
use PhpOffice\PhpSpreadsheet\Exception;
use PhpOffice\PhpSpreadsheet\IOFactory;


class ImportadorExcel
{

    public function importarDatos($file)
    {
        # Abrir el documento
        $documento = IOFactory::load($file);

        # Recuerda que un documento puede tener múltiples hojas
        # obtener conteo e iterar
        $totalDeHojas = $documento->getSheetCount();
        # obtener todos los nombres de las hojas
        $hojaNombre = $documento->getSheetNames();

        # Variables
        # Declaración de coordenadas
        # datos generales
        $coordenadaEncuesta = "E8";
        $coordenadaCI = "I8";
        $coordenadaFechaValoracion = "H13";
        $coordenadaPoliclinico = "H15";
        $coordenadaPrimerApellido = "H19";
        $coordenadaSegundoApellido = "P19";
        $coordenadaNombre = "W19";
        $coordenadaFechaNacimiento = "H21";
        $coordenadaEdad = "P21";
        $coordenadaEstadoCivil = "W21";
        $coordenadaMunicipio = "P23";
        $coordenadaSexo = "W23";
        $coordenadaEmpleoInicio = "26";
        $coordenadaEmpleoFinal = "33";
        #diagnostico
        $coordenadaGradoInicio = "38";
        $coordenadaGradoFinal = "40";
        $coordenadaFactorInicio = "38";
        $coordenadaFactorFinal = "44";
        $coordenadaDiagnosticoInicio = "38";
        $coordenadaDiagnosticoFinal = "49";
        $coordenadaSistemaInicio = "52";
        $coordenadaSistemaFinal = "59";
        $coordenadaFuncionInicio = "52";
        $coordenadaFuncionFinal = "58";
        #dominios
        $coordenadaD1Inicio = "80";
        $coordenadaD1Final = "80";
        $coordenadaD2Inicio = "86";
        $coordenadaD2Final = "88";
        $coordenadaD3Inicio = "94";
        $coordenadaD3Final = "95";
        $coordenadaD4Inicio = "101";
        $coordenadaD4Final = "111";
        $coordenadaD5Inicio = "118";
        $coordenadaD5Final = "123";
        $coordenadaD6Inicio = "129";
        $coordenadaD6Final = "130";
        $coordenadaD7Inicio = "136";
        $coordenadaD7Final = "140";
        $coordenadaD8Inicio = "146";
        $coordenadaD8Final = "159";
        $coordenadaD9Inicio = "165";
        $coordenadaD9Final = "172";
        #entrevistadores
        $coordenadaEntrevistadorInicio = "180";
        $coordenadaEntrevistadorFinal = "180";
        $resultados = array();
        $empleos = array();
        $grados = array();
        $factores = array();
        $diagnosticos = array();
        $sistemas = array();
        $funciones = array();
        $preguntasD1 = array();
        $preguntasD2 = array();
        $preguntasD3 = array();
        $preguntasD4 = array();
        $preguntasD5 = array();
        $preguntasD6 = array();
        $preguntasD7 = array();
        $preguntasD8 = array();
        $preguntasD9 = array();
        $entrevistadores = array();
        # Iterar hoja por hoja
        for ($indiceHoja = 0; $indiceHoja < $totalDeHojas; $indiceHoja++) {

            # Obtener hoja en el índice que vaya del ciclo
            $hojaActual = $documento->getSheet($indiceHoja);

            #Verficar si la hoja es una encuesta
            if ($hojaActual->getCell($coordenadaEncuesta)->getFormattedValue() !== 'Carné de identidad:') {
                #Continua el ciclo con la próxima hoja si existe
                continue;
            }

            #Validación de datos
            if ($hojaActual->getCell($coordenadaCI)->getFormattedValue() === '') {
                $resultados[] = array(
                    'valido' => false,
                    'encuesta' => '-',
                    'estado' => 'no',
                    'motivo' => 'El Carné de identidad no puede estar en blanco.'
                );
                #Continua el ciclo con la próxima hoja si existe
                continue;
            }

            if ($hojaActual->getCell($coordenadaFechaValoracion)->getFormattedValue() === '') {
                $resultados[] = array(
                    'valido' => false,
                    'encuesta' => $hojaActual->getCell($coordenadaCI)->getFormattedValue(),
                    'estado' => 'no',
                    'motivo' => 'La fecha de valoración no puede estar en blanco.'
                );
                #Continua el ciclo con la próxima hoja si existe
                continue;
            }

            if ($hojaActual->getCell($coordenadaPoliclinico)->getFormattedValue() === '') {
                $resultados[] = array(
                    'valido' => false,
                    'encuesta' => $hojaActual->getCell($coordenadaCI)->getFormattedValue(),
                    'estado' => 'no',
                    'motivo' => 'El policlínico no puede estar en blanco.'
                );
                #Continua el ciclo con la próxima hoja si existe
                continue;
            }

            if ($hojaActual->getCell($coordenadaPrimerApellido)->getFormattedValue() === '') {
                $resultados[] = array(
                    'valido' => false,
                    'encuesta' => $hojaActual->getCell($coordenadaCI)->getFormattedValue(),
                    'estado' => 'no',
                    'motivo' => 'El primer apellido no puede estar en blanco.'
                );
                #Continua el ciclo con la próxima hoja si existe
                continue;
            }

            if ($hojaActual->getCell($coordenadaSegundoApellido)->getFormattedValue() === '') {
                $resultados[] = array(
                    'valido' => false,
                    'encuesta' => $hojaActual->getCell($coordenadaCI)->getFormattedValue(),
                    'estado' => 'no',
                    'motivo' => 'El segundo apellido no puede estar en blanco.'
                );
                #Continua el ciclo con la próxima hoja si existe
                continue;
            }

            if ($hojaActual->getCell($coordenadaNombre)->getFormattedValue() === '') {
                $resultados[] = array(
                    'valido' => false,
                    'encuesta' => $hojaActual->getCell($coordenadaCI)->getFormattedValue(),
                    'estado' => 'no',
                    'motivo' => 'El nombre no puede estar en blanco.'
                );
                #Continua el ciclo con la próxima hoja si existe
                continue;
            }

            if ($hojaActual->getCell($coordenadaFechaNacimiento)->getFormattedValue() === '') {
                $resultados[] = array(
                    'valido' => false,
                    'encuesta' => $hojaActual->getCell($coordenadaCI)->getFormattedValue(),
                    'estado' => 'no',
                    'motivo' => 'La fecha de nacimiento no puede estar en blanco.'
                );
                #Continua el ciclo con la próxima hoja si existe
                continue;
            }

            if ($hojaActual->getCell($coordenadaEdad)->getFormattedValue() === '') {
                $resultados[] = array(
                    'valido' => false,
                    'encuesta' => $hojaActual->getCell($coordenadaCI)->getFormattedValue(),
                    'estado' => 'no',
                    'motivo' => 'La edad no puede estar en blanco.'
                );
                #Continua el ciclo con la próxima hoja si existe
                continue;
            }

            if ($hojaActual->getCell($coordenadaEstadoCivil)->getFormattedValue() === '') {
                $resultados[] = array(
                    'valido' => false,
                    'encuesta' => $hojaActual->getCell($coordenadaCI)->getFormattedValue(),
                    'estado' => 'no',
                    'motivo' => 'El estado civil no puede estar en blanco.'
                );
                #Continua el ciclo con la próxima hoja si existe
                continue;
            }

            if ($hojaActual->getCell($coordenadaMunicipio)->getFormattedValue() === '') {
                $resultados[] = array(
                    'valido' => false,
                    'encuesta' => $hojaActual->getCell($coordenadaCI)->getFormattedValue(),
                    'estado' => 'no',
                    'motivo' => 'El municipio de residencia no puede estar en blanco.'
                );
                #Continua el ciclo con la próxima hoja si existe
                continue;
            }

            if ($hojaActual->getCell($coordenadaSexo)->getFormattedValue() === '') {
                $resultados[] = array(
                    'valido' => false,
                    'encuesta' => $hojaActual->getCell($coordenadaCI)->getFormattedValue(),
                    'estado' => 'no',
                    'motivo' => 'El sexo no puede estar en blanco.'
                );
                #Continua el ciclo con la próxima hoja si existe
                continue;
            }

            #empleo
            $empleos = [];
            for ($i = $coordenadaEmpleoInicio; $i <= $coordenadaEmpleoFinal; $i++) {
                if ($hojaActual->getCell('O' . $i)->getCalculatedValue()) {
                    $empleos[] = $hojaActual->getCell('G' . $i)->getFormattedValue();
                }
            }

            if(count($empleos) === 0) {
                $resultados[] = array(
                    'valido' => false,
                    'encuesta' => $hojaActual->getCell($coordenadaCI)->getFormattedValue(),
                    'estado' => 'no',
                    'motivo' => 'Debe seleccionar al menos un empleo.'
                );
                #Continua el ciclo con la próxima hoja si existe
                continue;
            }

            #grado
            $grados = [];
            for ($i = $coordenadaGradoInicio; $i <= $coordenadaGradoFinal; $i++) {
                if ($hojaActual->getCell('I' . $i)->getCalculatedValue()) {
                    $grados[] = $hojaActual->getCell('C' . $i)->getFormattedValue();
                }
            }

            if(count($grados) === 0) {
                $resultados[] = array(
                    'valido' => false,
                    'encuesta' => $hojaActual->getCell($coordenadaCI)->getFormattedValue(),
                    'estado' => 'no',
                    'motivo' => 'Debe seleccionar al menos un grado de independencia.'
                );
                #Continua el ciclo con la próxima hoja si existe
                continue;
            }

            #factores
            $factores = [];
            for ($i = $coordenadaFactorInicio; $i <= $coordenadaFactorFinal; $i++) {
                if ($hojaActual->getCell('Q' . $i)->getCalculatedValue()) {
                    $factores[] = $hojaActual->getCell('J' . $i)->getFormattedValue();
                }
            }

            if(count($factores) === 0) {
                $resultados[] = array(
                    'valido' => false,
                    'encuesta' => $hojaActual->getCell($coordenadaCI)->getFormattedValue(),
                    'estado' => 'no',
                    'motivo' => 'Debe seleccionar al menos un factor de riesgo.'
                );
                #Continua el ciclo con la próxima hoja si existe
                continue;
            }

            #diagnosticos medicos
            $diagnosticos = [];
            for ($i = $coordenadaFactorInicio; $i <= $coordenadaFactorFinal; $i++) {
                if ($hojaActual->getCell('Z' . $i)->getCalculatedValue()) {
                    $diagnosticos[] = $hojaActual->getCell('R' . $i)->getFormattedValue();
                }
            }

            if(count($diagnosticos) === 0) {
                $resultados[] = array(
                    'valido' => false,
                    'encuesta' => $hojaActual->getCell($coordenadaCI)->getFormattedValue(),
                    'estado' => 'no',
                    'motivo' => 'Debe seleccionar al menos un diagnóstico médico.'
                );
                #Continua el ciclo con la próxima hoja si existe
                continue;
            }

            #sistemas afectados
            $sistemas = [];
            for ($i = $coordenadaSistemaInicio; $i <= $coordenadaSistemaFinal; $i++) {
                if ($hojaActual->getCell('J' . $i)->getCalculatedValue()) {
                    $sistemas[] = $hojaActual->getCell('C' . $i)->getFormattedValue();
                }
            }

            if(count($sistemas) === 0) {
                $resultados[] = array(
                    'valido' => false,
                    'encuesta' => $hojaActual->getCell($coordenadaCI)->getFormattedValue(),
                    'estado' => 'no',
                    'motivo' => 'Debe seleccionar al menos un sistema afectado.'
                );
                #Continua el ciclo con la próxima hoja si existe
                continue;
            }

            #funciones afectados
            $funciones = [];
            for ($i = $coordenadaFuncionInicio; $i <= $coordenadaFuncionFinal; $i++) {
                if ($hojaActual->getCell('R' . $i)->getCalculatedValue()) {
                    $funciones[] = $hojaActual->getCell('K' . $i)->getFormattedValue();
                }
            }

            if (count($funciones) === 0) {
                $resultados[] = array(
                    'valido' => false,
                    'encuesta' => $hojaActual->getCell($coordenadaCI)->getFormattedValue(),
                    'estado' => 'no',
                    'motivo' => 'Debe seleccionar al menos una función afectada.'
                );
                #Continua el ciclo con la próxima hoja si existe
                continue;
            }

            #d1
            $preguntasD1 = [];
            for ($i = $coordenadaD1Inicio; $i <= $coordenadaD1Final; $i++) {
                if ($hojaActual->getCell('P' . $i)->getFormattedValue() !== '') {
                    $preguntasD1[] =
                        $hojaActual->getCell('D' . $i)->getFormattedValue() . '$' .
                        $hojaActual->getCell('P' . $i)->getFormattedValue();
                }
            }

            #d2
            $preguntasD2 = [];
            for ($i = $coordenadaD2Inicio; $i <= $coordenadaD2Final; $i++) {
                if ($hojaActual->getCell('P' . $i)->getFormattedValue() !== '') {
                    $preguntasD2[] =
                        $hojaActual->getCell('D' . $i)->getFormattedValue() . '$' .
                        $hojaActual->getCell('P' . $i)->getFormattedValue();
                }
            }

            #d3
            $preguntasD3 = [];
            for ($i = $coordenadaD3Inicio; $i <= $coordenadaD3Final; $i++) {
                if ($hojaActual->getCell('P' . $i)->getFormattedValue() !== '') {
                    $preguntasD3[] =
                        $hojaActual->getCell('D' . $i)->getFormattedValue() . '$' .
                        $hojaActual->getCell('P' . $i)->getFormattedValue();
                }
            }

            #d4
            $preguntasD4 = [];
            for ($i = $coordenadaD4Inicio; $i <= $coordenadaD4Final; $i++) {
                if ($hojaActual->getCell('P' . $i)->getFormattedValue() !== '') {
                    $preguntasD4[] =
                        $hojaActual->getCell('D' . $i)->getFormattedValue() . '$' .
                        $hojaActual->getCell('P' . $i)->getFormattedValue();
                }
            }

            #d5
            $preguntasD5 = [];
            for ($i = $coordenadaD5Inicio; $i <= $coordenadaD5Final; $i++) {
                if ($hojaActual->getCell('P' . $i)->getFormattedValue() !== '') {
                    $preguntasD5[] =
                        $hojaActual->getCell('D' . $i)->getFormattedValue() . '$' .
                        $hojaActual->getCell('P' . $i)->getFormattedValue();
                }
            }

            #d6
            $preguntasD6 = [];
            for ($i = $coordenadaD6Inicio; $i <= $coordenadaD6Final; $i++) {
                if ($hojaActual->getCell('P' . $i)->getFormattedValue() !== '') {
                    $preguntasD6[] =
                        $hojaActual->getCell('D' . $i)->getFormattedValue() . '$' .
                        $hojaActual->getCell('P' . $i)->getFormattedValue();
                }
            }

            #d7
            $preguntasD7 = [];
            for ($i = $coordenadaD7Inicio; $i <= $coordenadaD7Final; $i++) {
                if ($hojaActual->getCell('P' . $i)->getFormattedValue() !== '') {
                    $preguntasD7[] =
                        $hojaActual->getCell('D' . $i)->getFormattedValue() . '$' .
                        $hojaActual->getCell('P' . $i)->getFormattedValue();
                }
            }

            #d8
            $preguntasD8 = [];
            for ($i = $coordenadaD8Inicio; $i <= $coordenadaD8Final; $i++) {
                if ($hojaActual->getCell('P' . $i)->getFormattedValue() !== '' && $hojaActual->getCell('P' . $i)->getFormattedValue() !== 'Respuesta') {
                    $preguntasD8[] =
                        $hojaActual->getCell('D' . $i)->getFormattedValue() . '$' .
                        $hojaActual->getCell('P' . $i)->getFormattedValue();
                }
            }

            #d9
            $preguntasD9 = [];
            for ($i = $coordenadaD9Inicio; $i <= $coordenadaD9Final; $i++) {
                if ($hojaActual->getCell('P' . $i)->getFormattedValue() !== '') {
                    $preguntasD9[] =
                        $hojaActual->getCell('D' . $i)->getFormattedValue() . '$' .
                        $hojaActual->getCell('P' . $i)->getFormattedValue();
                }
            }

            #entrevistadores
            $entrevistadores = [];
            for ($i = $coordenadaEntrevistadorInicio; $i <= $coordenadaEntrevistadorFinal; $i++) {
                if ($hojaActual->getCell('J' . $i)->getCalculatedValue()) {
                    $entrevistadores[] = $hojaActual->getCell('C' . $i)->getFormattedValue();
                }
            }

            #Conformar los arreglos para insertar
            $resultados[] = array(
                'valido' => true,
                'encuesta' => $hojaActual->getCell($coordenadaCI)->getFormattedValue(),
                'estado' => 'si',
                'motivo' => '-',
                'ci' => $hojaActual->getCell($coordenadaCI)->getFormattedValue(),
                'nombre' => $hojaActual->getCell($coordenadaNombre)->getFormattedValue(),
                'primerApellido' => $hojaActual->getCell($coordenadaPrimerApellido)->getFormattedValue(),
                'segundoApellido' => $hojaActual->getCell($coordenadaSegundoApellido)->getFormattedValue(),
                'fechaNacimiento' => $hojaActual->getCell($coordenadaFechaNacimiento)->getFormattedValue(),
                'sexo' => $hojaActual->getCell($coordenadaSexo)->getFormattedValue(),
                'fechaValoracion' => $hojaActual->getCell($coordenadaFechaValoracion)->getFormattedValue(),
                'policlinico' => $hojaActual->getCell($coordenadaPoliclinico)->getFormattedValue(),
                'municipioResidencia' => $hojaActual->getCell($coordenadaMunicipio)->getFormattedValue(),
                'edad' => $hojaActual->getCell($coordenadaEdad)->getFormattedValue(),
                'estadoCivil' => $hojaActual->getCell($coordenadaEstadoCivil)->getFormattedValue(),
                'empleos' => $empleos,
                'grados' => $grados,
                'factores' => $factores,
                'diagnosticos' => $diagnosticos,
                'sistemas' => $grados,
                'funciones' => $funciones,
                'preguntasD1' => $preguntasD1,
                'preguntasD2' => $preguntasD2,
                'preguntasD3' => $preguntasD3,
                'preguntasD4' => $preguntasD4,
                'preguntasD5' => $preguntasD5,
                'preguntasD6' => $preguntasD6,
                'preguntasD7' => $preguntasD7,
                'preguntasD8' => $preguntasD8,
                'preguntasD9' => $preguntasD9,
                'entrevistadores' => $entrevistadores,
            );
        }

        if (count($resultados) === 0) {
            $resultados->add(array(
                'valido' => false,
                'motivo' => 'No existen encuestas en el documento excel.'
            ));
            return $resultados;
        }

        return $resultados;

    }
}