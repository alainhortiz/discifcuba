<?php

namespace AppBundle\ExportacionExcel;


use PhpOffice\PhpSpreadsheet\Cell\DataType;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Borders;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;


class ExportadorExcel
{

    public function exportarDominio($username, $titulo, $nombreHoja,$nombreDocumento,$encabezado,$resp,$entrevistados, $noTabla)
    {
        $objPHPExcel = new Spreadsheet();

        $objPHPExcel->getProperties()
            ->setCreator($username)
            ->setLastModifiedBy($username)
            ->setTitle("Dominio")
            ->setSubject("Dominio")
            ->setDescription("Documento generado con DisCifCuba")
            ->setKeywords("DISCIFCUBA")
            ->setCategory("DOMINIOS");

        //inicio del codigo de la hoja de totales
        $activeSheet = 0;

        for ($i = 'A'; $i <= 'M'; $i++) {
            if ($i === 'A') {
                $objPHPExcel->setActiveSheetIndex($activeSheet)->getColumnDimension($i)->setWidth(78);
            } else {
                $objPHPExcel->setActiveSheetIndex($activeSheet)->getColumnDimension($i)->setWidth(11);
            }
        }

        //Contenido
        $fila = 1;

        foreach ($resp as $r) {
            //titulo
            $filaInicio = $fila;
            //Dibujar Titulos
            $objPHPExcel->setActiveSheetIndex($activeSheet)->setCellValue('A'. $fila, "Tabla " . $noTabla . ". " .$titulo);
            $objPHPExcel->getActiveSheet()->getStyle('A'. $fila)->applyFromArray($this->estiloTituloReporte2());
            $noTabla++;
            //Dibujar encabezados
            $fila++;
            $objPHPExcel->setActiveSheetIndex($activeSheet)->setCellValue('A' . $fila, $encabezado);
            $objPHPExcel->getActiveSheet()->getStyle('A'. $fila)->applyFromArray($this->estiloTituloReporte());
            $fila++;
            $objPHPExcel->setActiveSheetIndex($activeSheet)->setCellValue('A' . $fila, 'Limitaciones');
            $objPHPExcel->setActiveSheetIndex($activeSheet)->setCellValue('B' . $fila, 'No Aplica');
            $objPHPExcel->setActiveSheetIndex($activeSheet)->setCellValue('D' . $fila, 'Ninguna');
            $objPHPExcel->setActiveSheetIndex($activeSheet)->setCellValue('F' . $fila, 'Con limitación');
            $fila++;
            $objPHPExcel->setActiveSheetIndex($activeSheet)->setCellValue('F' . $fila, 'Leve');
            $objPHPExcel->setActiveSheetIndex($activeSheet)->setCellValue('H' . $fila, 'Moderada');
            $objPHPExcel->setActiveSheetIndex($activeSheet)->setCellValue('J' . $fila, 'Severa');
            $objPHPExcel->setActiveSheetIndex($activeSheet)->setCellValue('L' . $fila, 'No lo puede hacer');
            $objPHPExcel->setActiveSheetIndex($activeSheet)->setCellValue('N' . $fila, 'Total');
            $fila++;
            $objPHPExcel->setActiveSheetIndex($activeSheet)->setCellValue('B' . $fila, 'Número');
            $objPHPExcel->setActiveSheetIndex($activeSheet)->setCellValue('C' . $fila, '%');
            $objPHPExcel->setActiveSheetIndex($activeSheet)->setCellValue('D' . $fila, 'Número');
            $objPHPExcel->setActiveSheetIndex($activeSheet)->setCellValue('E' . $fila, '%');
            $objPHPExcel->setActiveSheetIndex($activeSheet)->setCellValue('F' . $fila, 'Número');
            $objPHPExcel->setActiveSheetIndex($activeSheet)->setCellValue('G' . $fila, '%');
            $objPHPExcel->setActiveSheetIndex($activeSheet)->setCellValue('H' . $fila, 'Número');
            $objPHPExcel->setActiveSheetIndex($activeSheet)->setCellValue('I' . $fila, '%');
            $objPHPExcel->setActiveSheetIndex($activeSheet)->setCellValue('J' . $fila, 'Número');
            $objPHPExcel->setActiveSheetIndex($activeSheet)->setCellValue('K' . $fila, '%');
            $objPHPExcel->setActiveSheetIndex($activeSheet)->setCellValue('L' . $fila, 'Número');
            $objPHPExcel->setActiveSheetIndex($activeSheet)->setCellValue('M' . $fila, '%');
            $objPHPExcel->setActiveSheetIndex($activeSheet)->setCellValue('N' . $fila, 'Número');
            $objPHPExcel->setActiveSheetIndex($activeSheet)->setCellValue('O' . $fila, '%');
            $objPHPExcel->setActiveSheetIndex($activeSheet)->mergeCells('A' . $filaInicio . ':O' . $filaInicio);
            $filaInicio++;
            $objPHPExcel->setActiveSheetIndex($activeSheet)->mergeCells('A' . $filaInicio. ':O' . $filaInicio);
            //Centrar el encabezado
            $objPHPExcel->getActiveSheet()->getStyle('A' . $filaInicio. ':O' . $filaInicio)->applyFromArray($this->estiloCenter());
            $filaInicio++;
            $filaInicio2 = $filaInicio + 1;
            $objPHPExcel->setActiveSheetIndex($activeSheet)->mergeCells('A' . $filaInicio . ':A' . $filaInicio2 );
            $objPHPExcel->setActiveSheetIndex($activeSheet)->mergeCells('B' . $filaInicio . ':C' . $filaInicio2 );
            //Centrar el encabezado
            $objPHPExcel->getActiveSheet()->getStyle('A' . $filaInicio. ':O' . $filaInicio2)->applyFromArray($this->estiloCenter());
            $objPHPExcel->setActiveSheetIndex($activeSheet)->mergeCells('D' . $filaInicio . ':E' . $filaInicio2 );
            //Centrar el encabezado
            $objPHPExcel->getActiveSheet()->getStyle('D' . $filaInicio. ':E' . $filaInicio2)->applyFromArray($this->estiloCenter());
            $objPHPExcel->setActiveSheetIndex($activeSheet)->mergeCells('F' . $filaInicio . ':O' . $filaInicio);
            //Centrar el encabezado
            $objPHPExcel->getActiveSheet()->getStyle('F' . $filaInicio. ':O' . $filaInicio2)->applyFromArray($this->estiloCenter());
            $objPHPExcel->setActiveSheetIndex($activeSheet)->mergeCells('F' . $filaInicio2 . ':G' . $filaInicio2);
            $objPHPExcel->setActiveSheetIndex($activeSheet)->mergeCells('H' . $filaInicio2 . ':I' . $filaInicio2);
            $objPHPExcel->setActiveSheetIndex($activeSheet)->mergeCells('J' . $filaInicio2 . ':K'. $filaInicio2);
            $objPHPExcel->setActiveSheetIndex($activeSheet)->mergeCells('L' . $filaInicio2 . ':M' . $filaInicio2);
            $objPHPExcel->setActiveSheetIndex($activeSheet)->mergeCells('N' . $filaInicio2 . ':O'. $filaInicio2);
            //Centrar el encabezado
            $objPHPExcel->getActiveSheet()->getStyle('F' . $filaInicio2. ':O' . $filaInicio2)->applyFromArray($this->estiloCenter());
            $filaInicio2++;
            //Centrar el encabezado
            $objPHPExcel->getActiveSheet()->getStyle('B' . $filaInicio2. ':O' . $filaInicio2)->applyFromArray($this->estiloCenter());
            //Negritas
            $objPHPExcel->getActiveSheet()->getStyle('A' . $filaInicio . ':O' . $fila)->applyFromArray($this->estiloNegritas());

            //Preguntas
            $fila++;
            $objPHPExcel->setActiveSheetIndex($activeSheet)->setCellValue('A' . $fila, $r['pregunta']);
            $objPHPExcel->getActiveSheet()->getStyle('A' . $fila)->applyFromArray($this->estiloNegritas());
            $objPHPExcel->setActiveSheetIndex($activeSheet)->setCellValue('B' . $fila, $r['numeroNoAplica']);
            $objPHPExcel->setActiveSheetIndex($activeSheet)->setCellValue('C' . $fila, $r['porcientoNoAplica']);
            $objPHPExcel->getActiveSheet()->getStyle('C' . $fila)->getNumberFormat()->setFormatCode('#,##0.0');
            $objPHPExcel->setActiveSheetIndex($activeSheet)->setCellValue('D' . $fila, $r['numeroNinguna']);
            $objPHPExcel->setActiveSheetIndex($activeSheet)->setCellValue('E' . $fila, $r['porcientoNinguna']);
            $objPHPExcel->getActiveSheet()->getStyle('C' . $fila)->getNumberFormat()->setFormatCode('#,##0.0');
            $objPHPExcel->setActiveSheetIndex($activeSheet)->setCellValue('F' . $fila, $r['numeroLeve']);
            $objPHPExcel->setActiveSheetIndex($activeSheet)->setCellValue('G' . $fila, $r['porcientoLeve']);
            $objPHPExcel->getActiveSheet()->getStyle('E' . $fila)->getNumberFormat()->setFormatCode('#,##0.0');
            $objPHPExcel->setActiveSheetIndex($activeSheet)->setCellValue('H' . $fila, $r['numeroModerada']);
            $objPHPExcel->setActiveSheetIndex($activeSheet)->setCellValue('I' . $fila, $r['porcientoModerada']);
            $objPHPExcel->getActiveSheet()->getStyle('G' . $fila)->getNumberFormat()->setFormatCode('#,##0.0');
            $objPHPExcel->setActiveSheetIndex($activeSheet)->setCellValue('J' . $fila, $r['numeroSevera']);
            $objPHPExcel->setActiveSheetIndex($activeSheet)->setCellValue('K' . $fila, $r['porcientoSevera']);
            $objPHPExcel->getActiveSheet()->getStyle('I' . $fila)->getNumberFormat()->setFormatCode('#,##0.0');
            $objPHPExcel->setActiveSheetIndex($activeSheet)->setCellValue('L' . $fila, $r['numeroNoHacer']);
            $objPHPExcel->setActiveSheetIndex($activeSheet)->setCellValue('M' . $fila, $r['porcientoNoHacer']);
            $objPHPExcel->getActiveSheet()->getStyle('K' . $fila)->getNumberFormat()->setFormatCode('#,##0.0');
            $objPHPExcel->setActiveSheetIndex($activeSheet)->setCellValue('N' . $fila, $r['numeroTotal']);
            $objPHPExcel->setActiveSheetIndex($activeSheet)->setCellValue('O' . $fila, $r['porcientoTotal']);
            $objPHPExcel->getActiveSheet()->getStyle('M' . $fila)->getNumberFormat()->setFormatCode('#,##0.0');
            //Tamaño
            $objPHPExcel->getActiveSheet()->getStyle('A' . $filaInicio . ':O' . $fila)->applyFromArray($this->estiloTamaño());
            //Bordes
            $objPHPExcel->getActiveSheet()->getStyle('A' . $filaInicio . ':O' . $fila)->applyFromArray($this->estiloBordesVentas());

            //Salto de fila entre tablas
            $fila+= 3;

        }

        //plicar ajuste de texto a un rango:
        //$objPHPExcel->getActiveSheet()->getStyle('D1:E999')->getAlignment()->setWrapText(true);

        //Aplicar ajuste de texto a columna
        $objPHPExcel->getActiveSheet()->getStyle('A3:A'.$objPHPExcel->getActiveSheet()->getHighestRow())
            ->getAlignment()->setWrapText(true);

        $objPHPExcel->getActiveSheet()->setTitle($nombreHoja);

        //Fin del codigo de la hoja de totales

        $objPHPExcel->setActiveSheetIndex(0);

        header('Content-type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="' . $nombreDocumento . '.xlsx"');
        header('Cache-Control: max-age=0');
        $writer = new Xlsx($objPHPExcel);
        $writer->save('php://output');

        exit;

    }

    public function exportarIndicadorSexoEdad($username, $titulo, $nombreHoja,$nombreDocumento,$encabezado,$resp,$entrevistados)
    {
        $objPHPExcel = new Spreadsheet();

        $objPHPExcel->getProperties()
            ->setCreator($username)
            ->setLastModifiedBy($username)
            ->setTitle("SexoEdad")
            ->setSubject("SexoEdad")
            ->setDescription("Documento generado con DisCifCuba")
            ->setKeywords("DISCIFCUBA")
            ->setCategory("SEXO_EDAD");

        //inicio del codigo de la hoja de totales
        $activeSheet = 0;

        //titulo
        $objPHPExcel->setActiveSheetIndex($activeSheet)->setCellValue('A1', $titulo);

        //Encabezados
        $objPHPExcel->setActiveSheetIndex($activeSheet)->setCellValue('A3', $encabezado);
        $objPHPExcel->setActiveSheetIndex($activeSheet)->setCellValue('B3', 'SEXO Y GRUPO DE EDAD');
        $objPHPExcel->setActiveSheetIndex($activeSheet)->setCellValue('B4', 'M');
        $objPHPExcel->setActiveSheetIndex($activeSheet)->setCellValue('I4', 'F');
        $objPHPExcel->setActiveSheetIndex($activeSheet)->setCellValue('B5', '0-4');
        $objPHPExcel->setActiveSheetIndex($activeSheet)->setCellValue('C5', '5-9');
        $objPHPExcel->setActiveSheetIndex($activeSheet)->setCellValue('D5', '10-14');
        $objPHPExcel->setActiveSheetIndex($activeSheet)->setCellValue('E5', '15-18');
        $objPHPExcel->setActiveSheetIndex($activeSheet)->setCellValue('F5', '19-59');
        $objPHPExcel->setActiveSheetIndex($activeSheet)->setCellValue('G5', '60 y +');
        $objPHPExcel->setActiveSheetIndex($activeSheet)->setCellValue('H5', 'Total');
        $objPHPExcel->setActiveSheetIndex($activeSheet)->setCellValue('I5', '0-4');
        $objPHPExcel->setActiveSheetIndex($activeSheet)->setCellValue('J5', '5-9');
        $objPHPExcel->setActiveSheetIndex($activeSheet)->setCellValue('K5', '10-14');
        $objPHPExcel->setActiveSheetIndex($activeSheet)->setCellValue('L5', '15-18');
        $objPHPExcel->setActiveSheetIndex($activeSheet)->setCellValue('M5', '19-59');
        $objPHPExcel->setActiveSheetIndex($activeSheet)->setCellValue('N5', '60 y +');
        $objPHPExcel->setActiveSheetIndex($activeSheet)->setCellValue('O5', 'Total');
        $objPHPExcel->setActiveSheetIndex($activeSheet)->mergeCells('A1:O1');
        $objPHPExcel->setActiveSheetIndex($activeSheet)->mergeCells('A3:A5');
        $objPHPExcel->setActiveSheetIndex($activeSheet)->mergeCells('B3:O3');
        $objPHPExcel->setActiveSheetIndex($activeSheet)->mergeCells('B4:H4');
        $objPHPExcel->setActiveSheetIndex($activeSheet)->mergeCells('I4:O4');

        $objPHPExcel->getActiveSheet(0)->freezePane('P6');

        //Formato de la hoja
        $objPHPExcel->getActiveSheet()->getStyle('A1:O1')->applyFromArray($this->estiloTituloReporte());
        $objPHPExcel->getActiveSheet()->getStyle('A3:O5')->applyFromArray($this->estiloEncabezadosColumnas());

        for ($i = 'A'; $i <= 'O'; $i++) {
            if ($i === 'A') {
                $objPHPExcel->setActiveSheetIndex($activeSheet)->getColumnDimension($i)->setWidth(50);
            } else {
                $objPHPExcel->setActiveSheetIndex($activeSheet)->getColumnDimension($i)->setWidth(10);
            }
        }

        //Contenido
        $fila = 6;

        foreach ($resp as $r) {
            $objPHPExcel->setActiveSheetIndex($activeSheet)->setCellValue('A' . $fila, $r['indicador']);
            $objPHPExcel->setActiveSheetIndex($activeSheet)->setCellValue('B' . $fila, $r['totalCeroCuatroM']);
            $objPHPExcel->setActiveSheetIndex($activeSheet)->setCellValue('C' . $fila, $r['totalCincoNueveM']);
            $objPHPExcel->setActiveSheetIndex($activeSheet)->setCellValue('D' . $fila, $r['totalDiezCatorceM']);
            $objPHPExcel->setActiveSheetIndex($activeSheet)->setCellValue('E' . $fila, $r['totalQuinceDieciochoM']);
            $objPHPExcel->setActiveSheetIndex($activeSheet)->setCellValue('F' . $fila, $r['totalDiecinueveCincuentinueveM']);
            $objPHPExcel->setActiveSheetIndex($activeSheet)->setCellValue('G' . $fila, $r['totalMayor59M']);
            $objPHPExcel->setActiveSheetIndex($activeSheet)->setCellValue('H' . $fila, $r['totalM']);
            $objPHPExcel->setActiveSheetIndex($activeSheet)->setCellValue('I' . $fila, $r['totalCeroCuatroF']);
            $objPHPExcel->setActiveSheetIndex($activeSheet)->setCellValue('J' . $fila, $r['totalCincoNueveF']);
            $objPHPExcel->setActiveSheetIndex($activeSheet)->setCellValue('K' . $fila, $r['totalDiezCatorceF']);
            $objPHPExcel->setActiveSheetIndex($activeSheet)->setCellValue('L' . $fila, $r['totalQuinceDieciochoF']);
            $objPHPExcel->setActiveSheetIndex($activeSheet)->setCellValue('M' . $fila, $r['totalDiecinueveCincuentinueveF']);
            $objPHPExcel->setActiveSheetIndex($activeSheet)->setCellValue('N' . $fila, $r['totalMayor59F']);
            $objPHPExcel->setActiveSheetIndex($activeSheet)->setCellValue('O' . $fila, $r['totalF']);
            $fila++;
        }

        $fila--;
        $objPHPExcel->getActiveSheet()->getStyle('A6:O' . $fila)->applyFromArray($this->estiloBordesVentas());

        $fila+= 2;
        $objPHPExcel->setActiveSheetIndex($activeSheet)->setCellValue('A' . $fila, 'Fuente: Sistema DisCifCuba');
        $objPHPExcel->getActiveSheet()->getStyle('A' . $fila . ':A' . $fila)->applyFromArray($this->estiloFooterReporte());
        $fila+= 2;
        $objPHPExcel->setActiveSheetIndex($activeSheet)->setCellValue('A' . $fila, 'Total de entrevistados: ' . $entrevistados);
        $objPHPExcel->getActiveSheet()->getStyle('A' . $fila . ':A' . $fila)->applyFromArray($this->estiloFooterReporte());

        $objPHPExcel->getActiveSheet()->setTitle($nombreHoja);

        //Fin del codigo de la hoja de totales

        $objPHPExcel->setActiveSheetIndex(0);

        header('Content-type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="' . $nombreDocumento . '.xlsx"');
        header('Cache-Control: max-age=0');
        $writer = new Xlsx($objPHPExcel);
        $writer->save('php://output');

        exit;

    }

    //Estilos
    private function estiloEncabezadosColumnasGradiente()
    {
        return [
            'font' => [
                'name' => 'Arial',
                'bold' => true,
                'italic' => false,
                'strike' => false,
                'size' => 12,
                'color' => [
                    'rgb' => '#222222'
                ]
            ],
            'fill' => [
                'fillType' => Fill::FILL_GRADIENT_LINEAR,
                'startColor' => [
                    'argb' => 'FFA0A0A0',
                ],
                'endColor' => [
                    'argb' => 'FFFFFFFF',
                ],
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                ],
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
                'rotation' => 0,
                'wrap' => TRUE
            ]
        ];


    }

    private function estiloEncabezadosColumnasMenor()
    {
        return [
            'font' => [
                'name' => 'Arial',
                'bold' => true,
                'italic' => false,
                'strike' => false,
                'size' => 10,
                'color' => [
                    'rgb' => '#222222'
                ]
            ],
            'fill' => [
                'fillType' => Fill::FILL_GRADIENT_LINEAR,
                'startColor' => [
                    'argb' => 'FFA0A0A0',
                ],
                'endColor' => [
                    'argb' => 'FFFFFFFF',
                ],
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                ],
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
                'rotation' => 0,
                'wrap' => TRUE
            ]
        ];


    }

    private function estiloCenter()
    {
        return [
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
                'rotation' => 0,
                'wrap' => TRUE
            ]
        ];


    }

    private function estiloNegritas()
    {
        return [
            'font' => [
                'name' => 'Arial',
                'bold' => true,
                'italic' => false,
                'strike' => false,
                'size' => 12,
                'color' => [
                    'rgb' => '#222222'
                ]
            ]
        ];


    }

    private function estiloNegritasPlan()
    {
        return [
            'font' => [
                'bold' => true,
                'color' => [
                    'rgb' => '#222222'
                ]
            ]
        ];


    }

    private function estiloColorFontRed()
    {
        return [
            'font' => [
                'bold' => false,
                'color' => [
                    'rgb' => '#222222'
                ]
            ]
        ];


    }

    private function estiloTituloReporte()
    {
        return array(
            'font' => array(
                'name' => 'Arial',
                'bold' => true,
                'italic' => false,
                'strike' => false,
                'size' => 12,
                'color' => array(
                    'rgb' => '111111'
                )
            ),
            'fill' => array(
                'type' => Fill::FILL_SOLID,
                'color' => array(
                    'rgb' => '#e95e25')
            ),
            'borders' => array(
                'allborders' => array(
                    'style' => Border::BORDER_MEDIUM,
                    'color' => array('argb' => '000000')
                )
            ),
            'alignment' => array(
                'horizontal' => Alignment::HORIZONTAL_CENTER_CONTINUOUS,
                'vertical' => Alignment::VERTICAL_CENTER,
                'rotation' => 0,
                'wrap' => TRUE
            )
        );
    }

    private function estiloTituloReporte2()
    {
        return array(
            'font' => array(
                'name' => 'Arial',
                'bold' => true,
                'italic' => false,
                'strike' => false,
                'size' => 12,
                'color' => array(
                    'rgb' => '111111'
                )
            ),
            'fill' => array(
                'type' => Fill::FILL_SOLID,
                'color' => array(
                    'rgb' => '#e95e25')
            ),
            'borders' => array(
                'allborders' => array(
                    'style' => Border::BORDER_MEDIUM,
                    'color' => array('argb' => '000000')
                )
            ),
            'alignment' => array(
                'horizontal' => Alignment::HORIZONTAL_LEFT,
                'vertical' => Alignment::VERTICAL_CENTER,
                'rotation' => 0,
                'wrap' => TRUE
            )
        );
    }

    private function estiloFooterReporte()
    {
        return array(
            'font' => array(
                'name' => 'Arial',
                'bold' => false,
                'italic' => false,
                'strike' => false,
                'size' => 12,
                'color' => array(
                    'rgb' => '111111'
                )
            )
        );
    }

    private function estiloTituloReporteVenta()
    {
        return array(
            'font' => array(
                'name' => 'Arial',
                'bold' => true,
                'italic' => false,
                'strike' => false,
                'size' => 12,
                'color' => array(
                    'rgb' => '111111'
                )
            ),
            'fill' => array(
                'type' => Fill::FILL_SOLID,
                'color' => array(
                    'rgb' => '#ffffff')
            ),
            'borders' => array(
                'allborders' => array(
                    'style' => Border::BORDER_MEDIUM,
                    'color' => array('argb' => '000000')
                )
            ),
            'alignment' => array(
                'horizontal' => Alignment::HORIZONTAL_CENTER_CONTINUOUS,
                'vertical' => Alignment::VERTICAL_CENTER,
                'rotation' => 0,
                'wrap' => TRUE
            )
        );
    }

    private function estiloEncabezadosColumnas()
    {
        return [
            'font' => [
                'name' => 'Arial',
                'bold' => true,
                'italic' => false,
                'strike' => false,
                'size' => 12,
                'color' => [
                    'rgb' => '#222222'
                ]
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                ],
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
                'rotation' => 0,
                'wrap' => TRUE
            ]
        ];


    }

    private function estiloDatos()
    {
        return [
            'font' => [
                'name' => 'Arial',
                'bold' => false,
                'italic' => false,
                'strike' => false,
                'size' => 12,
                'color' => [
                    'rgb' => '#222222'
                ]
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                ],
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
                'rotation' => 0,
                'wrap' => TRUE
            ]
        ];
    }

    private function estiloDatosPlan()
    {
        return [
            'font' => [
                'name' => 'Century Gothic',
                'bold' => false,
                'italic' => false,
                'strike' => false,
                'size' => 10,
                'color' => [
                    'rgb' => '#222222'
                ]
            ]
        ];
    }

    private function estiloBordes()
    {
        return [
            'font' => [
                'name' => 'Arial',
                'size' => 10,
                'color' => [
                    'rgb' => '#222222'
                ]
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                ],
            ]
        ];
    }

    private function estiloBordesVentas()
    {
        return [
            'font' => [
                'name' => 'Arial',
                'size' => 12,
                'color' => [
                    'rgb' => '#222222'
                ]
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                ],
            ]
        ];
    }

    private function estiloTamaño()
    {
        return [
            'font' => [
                'name' => 'Arial',
                'size' => 12,
                'color' => [
                    'rgb' => '#222222'
                ]
            ]
        ];
    }


}