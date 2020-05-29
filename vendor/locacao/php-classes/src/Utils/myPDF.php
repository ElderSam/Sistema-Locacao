<?php

require __DIR__ . "/../../../../autoload.php"; //vendor/autoload

use Dompdf\Dompdf;

$dompdf = new Dompdf();

$dompdf->load_html("<h1>Hello World!</h1>"); //carrega o conteúdo passado por parâmtro
$dompdf->render();

$file_name = "example.pdf";
$dompdf->stream($file_name, ["Attachment" => false]); //mostra o PDF na tela

/*$dompdf->stream($file_name); */ //baixa o PDF

/* //baixa o PDF no servidor (na mesma pasta)
$file = $dompdf->output();
file_put_contents($file_name, $file);*/
