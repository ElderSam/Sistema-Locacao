<?php

namespace Locacao\Utils;

//require_once __DIR__ . '../vendor/autoload.php';

//use PHPMailer as GlobalPHPMailer;
use \PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

//Load composer's autoloader
require 'vendor/autoload.php';

    //You need enable less secure apps to access Gmail -> https://myaccount.google.com/lesssecureapps
    const USERNAME = "sistemalocacao2020@gmail.com";  //here your email
    const PASSWORD = "testetcc"; //here your password
    const NAME_FROM = "Sistema Locacao - TESTE";

class Mailer{

    private $mail;

    public function __construct($toAdress, $toName, $subject, $html, $file_name=false){

        //Create a new PHPMailer instance
        $this->mail = new PHPMailer();


        //Tell PHPMailer to use SMTP
        $this->mail->isSMTP();
        $this->mail->isHTML();
        $this->mail->setLanguage("br");

        $this->mail->SMTPOptions = array(
            'ssl' => array(
                'verify_peer' => false,
                'verify_peer_name' => false,
                'verify_self_signed' => true
            )
        );

        //Enable SMTP debugging
        // SMTP::DEBUG_OFF (0) = off (for production use)
        // SMTP::DEBUG_CLIENT (1) = client messages
        // SMTP::DEBUG_SERVER (2) = client and server messages
        $this->mail->SMTPDebug = 0;
        $this->mail->Debugoutput = 'html';
        $this->Charset = "utf-8";

        //Set the hostname of the mail server
        $this->mail->Host = 'smtp.gmail.com';
        // use
        // $this->mail->Host = gethostbyname('smtp.gmail.com');
        // if your network does not support SMTP over IPv6

        //Set the SMTP port number - 587 for authenticated TLS, a.k.a. RFC4409 SMTP submission
        $this->mail->Port = 587;

        //Set the encryption mechanism to use - STARTTLS or SMTPS
        $this->mail->SMTPSecure = 'tls';

        //Whether to use SMTP authentication
        $this->mail->SMTPAuth = true;

        //Username to use for SMTP authentication - use full email address for gmail
        //$this->mail->Username = Mailer::USERNAME;
        $this->mail->Username = USERNAME;

        //Password to use for SMTP authentication
        //$this->mail->Password = Mailer::PASSWORD;
        $this->mail->Password = PASSWORD;

        //Set who the message is to be sent from
        //$this->mail->setFrom(Mailer::USERNAME, Mailer::NAME_FROM);
        $this->mail->setFrom(USERNAME, NAME_FROM);

        //Set an alternative reply-to address
        //$this->mail->addReplyTo('youremail@gmail.com', 'PHP7TESTE');

        //Set who the message is to be sent to
        $this->mail->addAddress($toAdress, $toName);

        //Set the subject line
        $this->mail->Subject = $subject;

        //Read an HTML message body from an external file, convert referenced images to embedded,
        //convert HTML into a basic plain-text alternative body
        $this->mail->msgHTML($html);

        //Replace the plain text body with one created manually
        $this->mail->AltBody = 'This is a plain-text message body';

        if($file_name){
            //Attach an image file
            //$this->mail->addAttachment('images/phpmailer_mini.png');
            
            $this->mail->AddAttachment($file_name);
        }

    }

    public function send(){

        return $this->mail->send();
    }

    
    public function getErrorInfo(){

        return 'Error: ' . $this->mail->ErrorInfo;
    }

}