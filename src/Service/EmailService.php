<?php

namespace Service;

use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;

class EmailService {

    private $adminEmail;
    private MailerInterface $mailer;

    public function __construct(string $adminEmail, MailerInterface $mailerInterface) {
        $this->$adminEmail = $adminEmail;
        $this->mailer = $mailerInterface;
    }

    public function sendEmail($emailUser, $attachedFile, $nameFile, $data, $subject, $template) {


        $email = (new TemplatedEmail())
        ->from($this->adminEmail)
        ->to($this->adminEmail)
        ->cc($emailUser)
        ->subject($subject)
        ->htmlTemplate($template)
        ->attach($attachedFile, $nameFile , date("Y-m-d"))
        ->context($data);

        $this->mailer->send($email);

    }
    
}


?>