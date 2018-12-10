<?php

header("Access-Control-Allow-Origin: *");
header('Content-Type: application/json');
include 'conexion.php';
include 'Classes/PHPExcel.php';

$fechaInicial = $_REQUEST["fechaInicial"];
$fechafinal = $_REQUEST["fechafinal"];

$info = DevolverUnArreglo("select * from auditoria");
$datosEmpresa = DevolverUnArreglo("select * from datosempresa");
$nombreempresa = $datosEmpresa[0]['NombreEmpresa'];
$NIT = $datosEmpresa[0]['nit'];

$fechaInicial = date("Y-m-d", strtotime($fechaInicial));
$fechafinal = date("Y-m-d", strtotime($fechafinal));

$estilo = array(
    'borders' => array(
        'outline' => array(
            'style' => PHPExcel_Style_Border::BORDER_THIN
        )
    )
);

$objPHPExcel = new PHPExcel();

$objPHPExcel->getProperties()->setCreator("codigo")->setDescription("Test document for PHPExcel, generated using PHP classes.");
$objPHPExcel->setActiveSheetIndex(0);


$objPHPExcel->setActiveSheetIndex(0)->mergeCells('A1:F1');
$objPHPExcel->setActiveSheetIndex(0)->mergeCells('A2:F2');
$objPHPExcel->getActiveSheet()->getStyle('A1:F1')->applyFromArray($estilo);
$objPHPExcel->getActiveSheet()->getStyle('A2:F2')->applyFromArray($estilo);
$objPHPExcel->getActiveSheet()->getStyle('A1:F1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$objPHPExcel->getActiveSheet()->getStyle('A2:F2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$objPHPExcel->getActiveSheet()->getStyle('A1')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('AFCEEB');
$objPHPExcel->getActiveSheet()->getStyle('A2')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('AFCEEB');
//$objPHPExcel->getActiveSheet()->getStyle('A1')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('9BC2E6');
//$objPHPExcel->getActiveSheet()->getStyle('A2')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('9BC2E6');
//INFO DE LA EMPRESA
$objPHPExcel->getActiveSheet()->setTitle("Ejemplo Nombre Pestaña");
$objPHPExcel->getActiveSheet()->setCellValue("A1", $nombreempresa);
$objPHPExcel->getActiveSheet()->setCellValue("A2", $NIT);


//FECHAS DE FILTRO
$objPHPExcel->getActiveSheet()->getStyle('A3:B3')->applyFromArray($estilo);
$objPHPExcel->getActiveSheet()->getStyle('A4:B4')->applyFromArray($estilo);
$objPHPExcel->getActiveSheet()->setCellValue("A3", "FECHA INICIAL");
$objPHPExcel->getActiveSheet()->setCellValue("B3", $fechaInicial);

$objPHPExcel->getActiveSheet()->setCellValue("A4", "FECHA FINAL");
$objPHPExcel->getActiveSheet()->setCellValue("B4", $fechafinal);

//REPORTE DE TURNOS
$objPHPExcel->getActiveSheet()->getStyle('A6:B9')->applyFromArray($estilo);
$TurnosCount = DevolverUnDato("select count(*) from auditoria where Estado = 'TERMINADO' or Estado = 'AUSENTE' and auditoria.FechaLlegada >= '$fechaInicial 00:00:00' and auditoria.FechaLlegada<='$fechafinal 23:59:59'");
$TurnosAtendidosCount = DevolverUnDato("select count(*) from auditoria where Estado = 'TERMINADO' and auditoria.FechaLlegada >= '$fechaInicial 00:00:00' and auditoria.FechaLlegada<='$fechafinal 23:59:59'");
$TurnosAusentesCount = DevolverUnDato("select count(*) from auditoria where Estado = 'AUSENTE' and auditoria.FechaLlegada >= '$fechaInicial 00:00:00' and auditoria.FechaLlegada<='$fechafinal 23:59:59'");
$objPHPExcel->setActiveSheetIndex(0)->mergeCells('A6:B6');
$objPHPExcel->getActiveSheet()->setCellValue("A6", "INFORMACION DE TURNOS");
$objPHPExcel->getActiveSheet()->setCellValue("A7", "TURNOS TOTALES");
$objPHPExcel->getActiveSheet()->setCellValue("B7", $TurnosCount);
$objPHPExcel->getActiveSheet()->setCellValue("A8", "TURNOS ATENDIDOS");
$objPHPExcel->getActiveSheet()->setCellValue("B8", $TurnosAtendidosCount);
$objPHPExcel->getActiveSheet()->setCellValue("A9", "TURNOS AUSENTES");
$objPHPExcel->getActiveSheet()->setCellValue("B9", $TurnosAusentesCount);
$objPHPExcel->getActiveSheet()->getStyle('A6')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('9BC2E6');
$objPHPExcel->getActiveSheet()->getStyle('A7')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('DDEBF7');
$objPHPExcel->getActiveSheet()->getStyle('B7')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('DDEBF7');
$objPHPExcel->getActiveSheet()->getStyle('A9')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('DDEBF7');
$objPHPExcel->getActiveSheet()->getStyle('B9')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('DDEBF7');
$objPHPExcel->getActiveSheet()->getStyle('A6:B6')->applyFromArray($estilo);
$objPHPExcel->getActiveSheet()->getStyle('A6:B6')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$objPHPExcel->getActiveSheet()->getStyle('B6:B9')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$contar = 13;

//REPORTE DE LOS SERVICIOS

$objPHPExcel->setActiveSheetIndex(0)->mergeCells('A11:C11');
$objPHPExcel->getActiveSheet()->setCellValue("A11", "INFORMACION DE LOS SERVICIOS");
$objPHPExcel->getActiveSheet()->setCellValue("A12", "SERVICIO");
$objPHPExcel->getActiveSheet()->setCellValue("B12", "TOTAL");
$objPHPExcel->getActiveSheet()->setCellValue("C12", "PORCENTAJE");

$servicios = DevolverUnArreglo("select servicio.Servicio, COUNT(auditoria.IdServicio) as Cantidad from auditoria JOIN servicio on (auditoria.IdServicio = servicio.IdServicio) where Estado = 'TERMINADO' or Estado = 'AUSENTE' and auditoria.FechaLlegada >= '$fechaInicial 00:00:00' and auditoria.FechaLlegada<='$fechafinal 23:59:59' GROUP by auditoria.IdServicio");
$ServiciosCount = DevolverUnDato("select count(*) from auditoria where Estado = 'TERMINADO' or Estado = 'AUSENTE' and auditoria.FechaLlegada >= '$fechaInicial 00:00:00' and auditoria.FechaLlegada<='$fechafinal 23:59:59'");
$totalPorcentaje = 0;
foreach ($servicios as &$valor) {
    $porcentaje = 0;

    $objPHPExcel->getActiveSheet()->setCellValue("A" . $contar, $valor['Servicio']);
    $objPHPExcel->getActiveSheet()->setCellValue("B" . $contar, $valor['Cantidad']);
    $porcentaje = ($valor['Cantidad'] / $ServiciosCount) * 100;
    $totalPorcentaje = $totalPorcentaje + $porcentaje;

    $objPHPExcel->getActiveSheet()->setCellValue("C" . $contar, round($porcentaje, 2) . "%");
    if ($contar % 2 == 1) {
        $objPHPExcel->getActiveSheet()->getStyle('A' . $contar . ':C' . $contar)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('DDEBF7');
    }
    $contar = $contar + 1;
}

$objPHPExcel->setActiveSheetIndex(0)->mergeCells('A' . $contar . ':B' . $contar);
$objPHPExcel->getActiveSheet()->setCellValue("A" . $contar, "TOTAL");
$objPHPExcel->getActiveSheet()->setCellValue("C" . $contar, $totalPorcentaje . "%");
$objPHPExcel->getActiveSheet()->getStyle('A11:C' . $contar)->applyFromArray($estilo);
if ($contar % 2 == 1) {
    $objPHPExcel->getActiveSheet()->getStyle('A' . $contar . ':C' . $contar)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('DDEBF7');
}
$objPHPExcel->getActiveSheet()->getStyle('B' . $contar . ':C' . $contar)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$contar = $contar + 2;
$objPHPExcel->getActiveSheet()->getStyle('A11')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('9BC2E6');
$objPHPExcel->getActiveSheet()->getStyle('A12')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('9BC2E6');
$objPHPExcel->getActiveSheet()->getStyle('B12')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('9BC2E6');
$objPHPExcel->getActiveSheet()->getStyle('C12')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('9BC2E6');
$objPHPExcel->getActiveSheet()->getStyle('A11:C11')->applyFromArray($estilo);
$objPHPExcel->getActiveSheet()->getStyle('A11:C11')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

//REPORTE TIPO POBLACION
$contTabla = $contar;
$objPHPExcel->setActiveSheetIndex(0)->mergeCells('A' . $contar . ':C' . $contar);
$objPHPExcel->getActiveSheet()->setCellValue("A" . $contar, "INFORMACION DE TIPO POBLACION");
$objPHPExcel->getActiveSheet()->getStyle('A' . $contar . ':C' . $contar)->applyFromArray($estilo);
$objPHPExcel->getActiveSheet()->getStyle('A' . $contar)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('9BC2E6');
$objPHPExcel->getActiveSheet()->getStyle('A' . $contar . ':B' . $contar)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$contar = $contar + 1;
$objPHPExcel->getActiveSheet()->setCellValue("A" . $contar, "POBLACION");
$objPHPExcel->getActiveSheet()->setCellValue("B" . $contar, "TOTAL");
$objPHPExcel->getActiveSheet()->setCellValue("C" . $contar, "PORCENTAJE");
$objPHPExcel->getActiveSheet()->getStyle('A' . $contar)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('9BC2E6');
$objPHPExcel->getActiveSheet()->getStyle('B' . $contar)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('9BC2E6');
$objPHPExcel->getActiveSheet()->getStyle('C' . $contar)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('9BC2E6');
$totalPorcentaje = 0;
$Poblacion = DevolverUnArreglo("select encuesta.TipoPoblacion, COUNT(encuesta.TipoPoblacion) as Cantidad from auditoria JOIN encuesta on (auditoria.IdEncuesta = encuesta.IdEncuesta) where Estado = 'TERMINADO' and auditoria.FechaLlegada >= '$fechaInicial 00:00:00' and auditoria.FechaLlegada<='$fechafinal 23:59:59' GROUP by encuesta.TipoPoblacion");
$poblacionContar = DevolverUnDato("select count(*) from auditoria where Estado = 'TERMINADO' and auditoria.FechaLlegada >= '$fechaInicial 00:00:00' and auditoria.FechaLlegada<='$fechafinal 23:59:59'");
$contar = $contar + 1;
foreach ($Poblacion as &$valor) {
    $porcentaje = 0;
    if ($contar % 2 == 1) {
        $objPHPExcel->getActiveSheet()->getStyle('A' . $contar . ':C' . $contar)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('DDEBF7');
    }

    $objPHPExcel->getActiveSheet()->setCellValue("A" . $contar, $valor['TipoPoblacion']);
    $objPHPExcel->getActiveSheet()->setCellValue("B" . $contar, $valor['Cantidad']);
    $porcentaje = ($valor['Cantidad'] / $poblacionContar) * 100;
    $totalPorcentaje = $totalPorcentaje + $porcentaje;
    $objPHPExcel->getActiveSheet()->setCellValue("C" . $contar, round($porcentaje, 2) . "%");
    $contar = $contar + 1;
}
if ($contar % 2 == 1) {
    $objPHPExcel->getActiveSheet()->getStyle('A' . $contar . ':C' . $contar)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('DDEBF7');
}
$objPHPExcel->setActiveSheetIndex(0)->mergeCells('A' . $contar . ':B' . $contar);
$objPHPExcel->getActiveSheet()->setCellValue("A" . $contar, "TOTAL");
$objPHPExcel->getActiveSheet()->setCellValue("C" . $contar, $totalPorcentaje . "%");
$objPHPExcel->getActiveSheet()->getStyle('A' . $contTabla . ':C' . $contar)->applyFromArray($estilo);
$objPHPExcel->getActiveSheet()->getStyle('B' . $contar . ':C' . $contar)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$contar = $contar + 2;



//DETALLE
$objPHPExcel->getActiveSheet()->setCellValue("A" . $contar, "ID");
$objPHPExcel->getActiveSheet()->setCellValue("B" . $contar, "SERVICIO");
$objPHPExcel->getActiveSheet()->setCellValue("C" . $contar, "ASESOR");
$objPHPExcel->getActiveSheet()->setCellValue("D" . $contar, "TURNO");
$objPHPExcel->getActiveSheet()->setCellValue("E" . $contar, "NOMBRE DEL USUARIO");
$objPHPExcel->getActiveSheet()->setCellValue("F" . $contar, "CEDULA");
$objPHPExcel->getActiveSheet()->setCellValue("G" . $contar, "ESTADO");
$objPHPExcel->getActiveSheet()->setCellValue("H" . $contar, "TIEMPO DE ESPERA EN SALA [hh:mm:ss]");
$objPHPExcel->getActiveSheet()->setCellValue("I" . $contar, "TIEMPO DE ATENCION [hh:mm:ss");
$objPHPExcel->getActiveSheet()->setCellValue("J" . $contar, "TIEMPO TOTAL [hh:mm:ss");
$objPHPExcel->getActiveSheet()->setCellValue("K" . $contar, "HORA Y FECHA DE SOLICITUD DE TURNO");
$objPHPExcel->getActiveSheet()->setCellValue("L" . $contar, "HORA Y FECHA DE LLAMADO DE TURNO");
$objPHPExcel->getActiveSheet()->setCellValue("M" . $contar, "HORA Y FECHA DE TERMINACION DE TURNO");
$objPHPExcel->getActiveSheet()->setCellValue("N" . $contar, "NUMERO DE LLAMADOS");
$objPHPExcel->getActiveSheet()->setCellValue("O" . $contar, "TIPO DE POBLACION");
$objPHPExcel->getActiveSheet()->setCellValue("P" . $contar, "SEXO");
$objPHPExcel->getActiveSheet()->setCellValue("Q" . $contar, "DIRECCION");
$objPHPExcel->getActiveSheet()->setCellValue("R" . $contar, "BARRIO");
$objPHPExcel->getActiveSheet()->setCellValue("S" . $contar, "TELEFONO");
$objPHPExcel->getActiveSheet()->setCellValue("T" . $contar, "NIVEL DE ESCOLARIDAD");
$objPHPExcel->getActiveSheet()->setCellValue("U" . $contar, "EDAD");
$objPHPExcel->getActiveSheet()->setCellValue("V" . $contar, "ASUNTO");
$objPHPExcel->getActiveSheet()->setCellValue("W" . $contar, "OBSERVACION DEL ASESOR");
$objPHPExcel->getActiveSheet()->getStyle('A' . $contar . ':W' . $contar)->applyFromArray($estilo);
$objPHPExcel->getActiveSheet()->getStyle('A' . $contar . ':W' . $contar)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('9BC2E6');
$objPHPExcel->getActiveSheet()->getStyle('A' . $contar . ':W' . $contar)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$contar = $contar + 1;

$detalle = DevolverUnArreglo("select auditoria.* , servicio.Servicio, usuario.NombreUsuario,personas.*,encuesta.* from personas,auditoria,servicio,usuario,encuesta where (auditoria.IdServicio = servicio.IdServicio) and (auditoria.IdUsuario = usuario.IdUsuario) and (auditoria.IdEncuesta = encuesta.IdEncuesta) and (auditoria.IdPersona = personas.IdPersona) and (auditoria.Estado = 'TERMINADO' or auditoria.Estado = 'AUSENTE') and auditoria.FechaLlegada >= '$fechaInicial 00:00:00' and auditoria.FechaLlegada<='$fechafinal 23:59:59'");
foreach ($detalle as &$valor) {
    if ($contar % 2 == 1) {
        $objPHPExcel->getActiveSheet()->getStyle('A' . $contar . ':W' . $contar)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('DDEBF7');
    }
    $objPHPExcel->getActiveSheet()->setCellValue("A" . $contar, $valor['IdAuditoria']);
    $objPHPExcel->getActiveSheet()->setCellValue("B" . $contar, $valor['Servicio']);
    $objPHPExcel->getActiveSheet()->setCellValue("C" . $contar, $valor['NombreUsuario']);
    $objPHPExcel->getActiveSheet()->setCellValue("D" . $contar, $valor['Turno']);
    $objPHPExcel->getActiveSheet()->setCellValue("E" . $contar, $valor['NombreCompleto']);
    $objPHPExcel->getActiveSheet()->setCellValue("F" . $contar, $valor['Cedula']);
    $objPHPExcel->getActiveSheet()->setCellValue("G" . $contar, $valor['Estado']);
    $TiempoEspera = CalcularMinutos(new DateTime($valor['FechaLlegada']), new DateTime($valor['FechaLlamado']));
    $TiempoAtencion = CalcularMinutos(new DateTime($valor['FechaLlamado']), new DateTime($valor['Fechasalio']));
    $tiempoTotal = $TiempoEspera + $TiempoAtencion;
    $objPHPExcel->getActiveSheet()->setCellValue("H" . $contar, conversorSegundosHoras($TiempoEspera));
    $objPHPExcel->getActiveSheet()->setCellValue("I" . $contar, conversorSegundosHoras($TiempoAtencion));
    $objPHPExcel->getActiveSheet()->setCellValue("J" . $contar, conversorSegundosHoras($tiempoTotal));
    $objPHPExcel->getActiveSheet()->setCellValue("K" . $contar, $valor['FechaLlegada']);
    $objPHPExcel->getActiveSheet()->setCellValue("L" . $contar, $valor['FechaLlamado']);
    $objPHPExcel->getActiveSheet()->setCellValue("M" . $contar, $valor['Fechasalio']);
    $objPHPExcel->getActiveSheet()->setCellValue("N" . $contar, $valor['NumeroLlamados']);
    $objPHPExcel->getActiveSheet()->setCellValue("O" . $contar, $valor['TipoPoblacion']);
    $objPHPExcel->getActiveSheet()->setCellValue("P" . $contar, $valor['Sexo']);
    $objPHPExcel->getActiveSheet()->setCellValue("Q" . $contar, $valor['Direccion']);
    $objPHPExcel->getActiveSheet()->setCellValue("R" . $contar, $valor['Barrio']);
    $objPHPExcel->getActiveSheet()->setCellValue("S" . $contar, $valor['Telefono']);
    $objPHPExcel->getActiveSheet()->setCellValue("T" . $contar, $valor['NivelEscolaridad']);
    $objPHPExcel->getActiveSheet()->setCellValue("U" . $contar, busca_edad($valor['FechaNacimiento']));
    $objPHPExcel->getActiveSheet()->setCellValue("V" . $contar, $valor['Asunto']);
    $objPHPExcel->getActiveSheet()->setCellValue("W" . $contar, $valor['Observacion']);
    $contar = $contar + 1;
}
//DETALLE LOS AUSENTES
$detalle2 = DevolverUnArreglo("select AUDITORIA.*, servicio.Servicio,usuario.NombreUsuario from auditoria,servicio,usuario where (auditoria.IdServicio = servicio.IdServicio) and (auditoria.IdUsuario = usuario.IdUsuario) and auditoria.Estado = 'AUSENTE' and auditoria.FechaLlegada >= '2018-03-10 00:00:00' and auditoria.FechaLlegada<='2018-12-10 23:59:59'");
foreach ($detalle2 as &$valor) {
    if ($contar % 2 == 1) {
        $objPHPExcel->getActiveSheet()->getStyle('A' . $contar . ':W' . $contar)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('DDEBF7');
    }
    $objPHPExcel->getActiveSheet()->setCellValue("A" . $contar, $valor['IdAuditoria']);
    $objPHPExcel->getActiveSheet()->setCellValue("C" . $contar, $valor['NombreUsuario']);
    $objPHPExcel->getActiveSheet()->setCellValue("D" . $contar, $valor['Turno']);
    $objPHPExcel->getActiveSheet()->setCellValue("G" . $contar, $valor['Estado']);
    $TiempoEspera = CalcularMinutos(new DateTime($valor['FechaLlegada']), new DateTime($valor['FechaLlamado']));
    $objPHPExcel->getActiveSheet()->setCellValue("H" . $contar, conversorSegundosHoras($TiempoEspera));
    $objPHPExcel->getActiveSheet()->setCellValue("K" . $contar, $valor['FechaLlegada']);
    $objPHPExcel->getActiveSheet()->setCellValue("L" . $contar, $valor['FechaLlamado']);
    $objPHPExcel->getActiveSheet()->setCellValue("N" . $contar, $valor['NumeroLlamados']);
    $objPHPExcel->getActiveSheet()->setCellValue("W" . $contar, $valor['Observacion']);
    $contar = $contar + 1;
}
$contar = $contar + 1;

$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('H')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('I')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('J')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('K')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('L')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('M')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('N')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('O')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('P')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('Q')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('R')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('S')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('T')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('U')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('V')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('W')->setAutoSize(true);


header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="Reporte de ' . $fechaInicial . ' a ' . $fechafinal . '.xlsx"');
header('Cache-Control: max-age=0');
$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
$objWriter->save('php://output');

function CalcularMinutos($fecha1, $fecha2) {
    $interval = $fecha1->diff($fecha2);
    $hours = $interval->format('%h');
    $minutes = $interval->format('%i');
    $segundos = $interval->format('%s');
    return ((($hours * 60) * 60) + ($minutes * 60) + $segundos);
}

function CalcularMinutos2($fecha1, $fecha2) {
    $interval = $fecha1->diff($fecha2);
    $hours = $interval->format('%h');
    $minutes = $interval->format('%i');
    return ($hours * 60) + $minutes;
}

function busca_edad($fecha_nacimiento) {
    $dia = date("d");
    $mes = date("m");
    $ano = date("Y");
    $dianaz = date("d", strtotime($fecha_nacimiento));
    $mesnaz = date("m", strtotime($fecha_nacimiento));
    $anonaz = date("Y", strtotime($fecha_nacimiento));
    if (($mesnaz == $mes) && ($dianaz > $dia)) {
        $ano = ($ano - 1);
    }
    if ($mesnaz > $mes) {
        $ano = ($ano - 1);
    }
    $edad = ($ano - $anonaz);
    return $edad;
}

function conversorSegundosHoras($tiempo_en_segundos) {
    $horas = floor($tiempo_en_segundos / 3600);
    $minutos = floor(($tiempo_en_segundos - ($horas * 3600)) / 60);
    $segundos = $tiempo_en_segundos - ($horas * 3600) - ($minutos * 60);

    return $horas . ':' . $minutos . ":" . $segundos;
}

?>
