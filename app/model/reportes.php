<?php

header("Access-Control-Allow-Origin: *");
header('Content-Type: application/json');
include 'conexion.php';
include 'Classes/PHPExcel.php';

    $objPHPExcel = new PHPExcel();
    $objPHPExcel->getProperties()->setCreator("codigo")->setDescription("Test document for PHPExcel, generated using PHP classes.");
    $objPHPExcel->setActiveSheetIndex(0);
    $objPHPExcel->getActiveSheet()->setTitle("Ejemplo Nombre Pestaña");
    $objPHPExcel->getActiveSheet()->setCellValue("A1", "perro");
    $objPHPExcel->getActiveSheet()->setCellValue("B1", "Gato");

    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment;filename="01simple.xlsx"');
    header('Cache-Control: max-age=0');
    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
    $objWriter->save('php://output');

//echo json_encode($validar, JSON_FORCE_OBJECT);
?>
