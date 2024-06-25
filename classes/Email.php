<?php

namespace Classes;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class Email{

    public $email;
    public $nombre;
    public $token;

    public function __construct($email, $nombre, $token){

        $this->email = $email;
        $this->nombre = $nombre;
        $this->token = $token;

    }

    public function enviarConfirmacion(){

         //Crear una instancia de mailer
         $mail = new PHPMailer();
            

         //Confirgurar SMTP
         $mail->isSMTP();
         $mail->Host = $_ENV['HOST'];
         $mail->SMTPAuth = true;
         $mail->Username = $_ENV['USER'];
         $mail->Password = $_ENV['PASS'];
         $mail->SMTPSecure = 'tls';
         $mail->Port = $_ENV['PORT'];

         //Configurar Contenido del email
         $mail->setFrom('cuentas@appsalon.com');
         $mail->addAddress('cuentas@appsalon.com', 'AppSalon.com');
         $mail->Subject = 'Confirma tu cuenta';

         //Habilitar HTML
         $mail->isHTML(true);
         $mail->CharSet = 'UTF-8';

         //Definir el contenido
        $contenido = '<html>';
        $contenido .= '<p><strong>Hola '. $this->nombre . '. Has creado tu cuenta en AppSalon.com. Debes confirmarla haciendo click en el siguiente enlace:</p>';
        $contenido .= '<p>Presiona aqui: <a href="' . $_ENV['APP_URL'] .'/confirmar-cuenta?token='.$this->token.'"> Confirmar Cuenta </a></p>';
        $contenido .= '<p>Si tu no solicitaste esta cuenta, puedes ignorar el mensaje</p>';
        $contenido .= '</html>';

        $mail->Body = $contenido;
        $mail->AltBody = "Texto alternativo sin html";

        $mail->send();

    }

    public function enviarInstrucciones(){

        //Crear una instancia de mailer
        $mail = new PHPMailer();
           

        //Confirgurar SMTP
        $mail->isSMTP();
        $mail->Host = $_ENV['HOST'];
        $mail->SMTPAuth = true;
        $mail->Username = $_ENV['USER'];
        $mail->Password = $_ENV['PASS'];
        $mail->SMTPSecure = 'tls';
        $mail->Port = $_ENV['PORT'];

        //Configurar Contenido del email
        $mail->setFrom('cuentas@appsalon.com');
        $mail->addAddress('cuentas@appsalon.com', 'AppSalon.com');
        $mail->Subject = 'Reestablece tu contraseña';

        //Habilitar HTML
        $mail->isHTML(true);
        $mail->CharSet = 'UTF-8';

        //Definir el contenido
       $contenido = '<html>';
       $contenido .= '<p><strong>Hola '. $this->nombre . '. Has solicitado reestablecer tu contraseña. Haz click en el siguiente enlace para continuar:</p>';
       $contenido .= '<p>Presiona aqui: <a href="' . $_ENV['APP_URL'] .'/recuperar?token='.$this->token.'"> Recuperar Contraseña </a></p>';
       $contenido .= '<p>Si tu no solicitaste este cambio, puedes ignorar el mensaje</p>';
       $contenido .= '</html>';

       $mail->Body = $contenido;
       $mail->AltBody = "Texto alternativo sin html";

       $mail->send();

   }


}