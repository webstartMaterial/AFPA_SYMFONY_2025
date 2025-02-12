<?php

namespace App\Service;

use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;

class EmailService {

    private string $adminEmail;
    private MailerInterface $mailer;

    public function __construct(MailerInterface $mailerInterface, string $adminEmail) {
        $this->adminEmail = $adminEmail;
        $this->mailer = $mailerInterface;
    }

    public function sendEmail($emailUser, $attachedFile, $nameFile, $data, $subject, $template) {


        $email = (new TemplatedEmail())
        ->from($this->adminEmail)
        ->to($this->adminEmail)
        ->cc($emailUser)
        ->subject($subject)
        ->htmlTemplate($template)
        ->attach($attachedFile, $nameFile)
        ->context($data);

        $this->mailer->send($email);

    }

    public function sendEmailNoAttachement($emailUser, $data, $subject, $template) {


        $email = (new TemplatedEmail())
        ->from($this->adminEmail)
        ->to($this->adminEmail)
        ->cc($emailUser)
        ->subject($subject)
        ->htmlTemplate($template)
        ->context($data);

        $this->mailer->send($email);

    }
    
}


?>