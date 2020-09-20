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
        $canvas = $this->dompdf->get_canvas();  
        $canvas->page_text(550, 755, "Pág. {PAGE_NUM}/{PAGE_COUNT}", 'Verdana', 10, array(0,0,0)); //footer
    }

    public function display()
    {
        $this->dompdf->stream($this->file_name, ["Attachment" => false]);
    }

    public function download()
    {
        $this->dompdf->stream($this->file_name);
    }

    public function sendEmail($toAdress, $toName, $subject, $html)
    {
        require_once __DIR__ . "/Mailer.php";

        //baixa o PDF
        $file = $this->dompdf->output();
		file_put_contents($this->file_name, $file);

		//sendMail($this->file_name); //envia o email
        
        /*$toAdress = 'exemplo@email.com';
        $toName = 'Nome do Destinatário';
        $subject = 'ORÇAMENTO - TCC';
        $html = 'REF: PROPOSTA DA EMPRESA X PARA LOCAÇÃO
        Olá, segue em anexo o arquivo PDF do Orçamento dos itens cotados para futura locação.<br>
            Atenciosamente,<br>
            Matheus Leite<br>
            COMFAL - locações de equipamentos para construções';*/

        $mail = new Mailer($toAdress, $toName, $subject, $html, $this->file_name);

        if ($mail->send()) { //envia o email, e verifica se retornou sucesso
            $error = false;
            $message = "Email enviado!";
            
        } else {
            $error = true;
            $txtError = $mail->getErrorInfo();
            $message = "Ops, algo deu errado<br>
                    <strong style='color: red;'> $txtError :(</strong><br>
                    Talvez algum email ou senha estão incorretos!<br>
                    <b>OBS:</b> para permitir enviar e-mail através do Gmail, você deve habilitar habilitar essa opção nesse <a href='https://myaccount.google.com/lesssecureapps' target='_blank'>link</a>!";
        }

        unlink($this->file_name);

        return json_encode([
            'error'=>$error,
            'msg'=>$message
        ]);
    }

}

/*$pdf = new myPDF();

$file_name = "example.pdf";
$content = "<h1>Hello World!</h1><p>It's an example!</p>";

$pdf->createPDF($file_name, $content);
$pdf->display();*/



