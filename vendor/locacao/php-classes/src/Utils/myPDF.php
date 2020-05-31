<?php

namespace Locacao\Utils;

use Exception;

require __DIR__ . "/../../../../autoload.php"; //vendor/autoload
use Dompdf\Dompdf;

//require __DIR__ . "/page.php"; //página HTML que será transformada em PDF
class myPDF{

    private $dompdf;
    private $file_name;

    public function __construct()
    {
        //$this->dompdf = new Dompdf();
        $this->dompdf = new Dompdf(["enable_remote" => true]);
    }

    public function createPDF($file_name, $content)
    {
        $this->file_name = $file_name;

        $this->dompdf->load_html($content);
        $this->dompdf->render();
    }

    public function display()
    {
        $this->dompdf->stream($this->file_name, ["Attachment" => false]);
    }

    public function download()
    {
        $this->dompdf->stream($this->file_name);
    }

}

/*$pdf = new myPDF();

$file_name = "example.pdf";
$content = "<h1>Hello World!</h1><p>It's an example!</p>";

$pdf->createPDF($file_name, $content);
$pdf->display();*/



