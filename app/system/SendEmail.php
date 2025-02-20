<?php

namespace bng\System;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

class SendEmail
{
    // =======================================================
    public function send_email($subject, $body, $data)
    {

        $mail = new PHPMailer(true);

        try {

            // IMPORTANT:
            // Uncomment to add server options from your email account provider
            // server settings

            //$mail->SMTPDebug = SMTP::DEBUG_SERVER;                      //Enable verbose debug output
            $mail->isSMTP();                                            //Send using SMTP
            $mail->Host       = EMAIL_HOST;                     //Set the SMTP server to send through
            $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
            $mail->Username   = EMAIL_USERNAME;                     //SMTP username
            $mail->Password   = EMAIL_PASSWORD;                               //SMTP password
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
            $mail->Port       = 465;     


            $mail->setFrom(EMAIL_FROM);
            $mail->addAddress($data['to']);

            $mail->isHTML(true);
            $mail->Subject = $subject;
            $mail->Body    = $this->$body($data);

            $mail->send();

            return [
                'status' => 'success'
            ];
        } catch (Exception $e) {

            return [
                'status' => 'error',
                'message' => $mail->ErrorInfo
            ];
        }
    }

    // =======================================================
    private function email_body_new_agent($data)
    {
        $html = '<p>Para concluir o processo de registo de agente, clique no link abaixo:</p>';
        $html .= '<a href="' . $data['link'] . '">Concluir registo de agente</a>';
        return $html;
    }
}
