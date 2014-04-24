<?php
$nombre_archivo = $_GET['file'];
$carpeta =$_GET['carpeta'];
//$file = "../layout_801/".$nombre_archivo;
$file = $carpeta.$nombre_archivo;

header("Content-disposition:attachment;filename=$nombre_archivo");
header("Content-type: application/octet-stream");
readfile($file);
?>