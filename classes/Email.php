<?php

    namespace Classes;

    use PHPMailer\PHPMailer\PHPMailer;

    class Email {

        public $email;
        public $nombre;
        public $token;
        
        public function __construct($email, $nombre, $token)
        {
            $this->email = $email;
            $this->nombre = $nombre;
            $this->token = $token;
        }

        public function enviarConfirmacion() {

            // create a new object
            $mail = new PHPMailer();
            $mail->isSMTP();
            $mail->Host = $_ENV['EMAIL_HOST'];
            $mail->SMTPAuth = true;
            $mail->Port = $_ENV['EMAIL_PORT'];
            $mail->Username = $_ENV['EMAIL_USER'];
            $mail->Password = $_ENV['EMAIL_PASS'];
        
            $mail->setFrom('cuentas@devwebcamp.com');
            $mail->addAddress($this->email, $this->nombre);
            $mail->Subject = 'Confirma tu cuenta';

            // Set HTML
            $mail->isHTML(TRUE);
            $mail->CharSet = 'UTF-8';

            $contenido = '<html>';
            $contenido .= "<p><strong>Hola " . $this->nombre .  "</strong> has registrado correctamente tu cuenta en DevWebCamp, pero es necesario confirmarla</p>";
            $contenido .= "<p>Presiona aquí: <a href='" . $_ENV['HOST'] . "/confirmar-cuenta?token=" . $this->token . "'>Confirmar cuenta</a>";       
            $contenido .= "<p>Si tu no creaste esta cuenta, puedes ignorar el mensaje</p>";
            $contenido .= '</html>';
            $mail->Body = $contenido;

            //Enviar el mail
            $mail->send();

        }

        public function enviarInstrucciones() {

            // create a new object
            $mail = new PHPMailer();
            $mail->isSMTP();
            $mail->Host = $_ENV['EMAIL_HOST'];
            $mail->SMTPAuth = true;
            $mail->Port = $_ENV['EMAIL_PORT'];
            $mail->Username = $_ENV['EMAIL_USER'];
            $mail->Password = $_ENV['EMAIL_PASS'];
        
            $mail->setFrom('cuentas@devwebcamp.com');
            $mail->addAddress($this->email, $this->nombre);
            $mail->Subject = 'Restablece tu password';

            // Set HTML
            $mail->isHTML(TRUE);
            $mail->CharSet = 'UTF-8';

            $contenido = '<html>';
            $contenido .= "<p><strong>Hola " . $this->nombre .  "</strong> has solicitado restablecer tu password, sigue el siguiente enlace para hacerlo</p>";
            $contenido .= "<p>Presiona aquí: <a href='" . $_ENV['HOST'] . "/restablecer?token=" . $this->token . "'>Restablecer password</a>";        
            $contenido .= "<p>Si tu no solicitaste este cambio, puedes ignorar este mensaje</p>";
            $contenido .= '</html>';
            $mail->Body = $contenido;

            //Enviar el mail
            $mail->send();
        }
    }