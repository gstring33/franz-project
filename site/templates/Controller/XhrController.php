<?php

namespace App\Controller;

use App\Core\TwigRenderer;
use App\Services\PHPMailerServices;
use App\Services\SecurityServices;
use ProcessWire\ProcessWire;
use ProcessWire\WireInput;

class XhrController
{
    private $securityServices;

    private $wire;

    private $twig;

    public function __construct()
    {
        $this->securityServices = new SecurityServices();
        $this->wire = ProcessWire::getCurrentInstance();
        $this->twig = new TwigRenderer();
    }

    public function contact(WireInput $request)
    {
        if ($request->requestMethod() !== 'POST') {
            return json_encode([]);
        }
        $data = $request->post()->getArray();

        if ($this->securityServices->isSpam($data)) {
            return json_encode([]);
        }

        $sanitizedData = $this->securityServices->sanitizeContactData($data);
        if (isset($sanitizedData['errors'])) {
            return json_encode([
                'status' => SecurityServices::ERROR_STATUS,
                'errors' => $sanitizedData['errors']
            ]);
        }

        $isRecaptchaValid = $this->securityServices->isRecaptchaValid($data['token']);
        if (isset($isRecaptchaValid['errors'])) {
            return json_encode([
                'status' => SecurityServices::ERROR_STATUS,
                'errors' => $isRecaptchaValid['errors']
            ]);
        }

        if ($this->wire->config->env === 'prod') {
            $emailContent = $this->twig->render('@email/contact.html.twig', [
                'contactMessage' => $sanitizedData['message'],
                'contactEmail' => $sanitizedData['email'],
                'contactName' => $sanitizedData['name']
            ]);

            $mailer = new PHPMailerServices();
            $mailer->send('martindhenu@yahoo.fr', "Neues Kontakt von Holzwerkerei67.de - " , $emailContent);
        }

        return json_encode([
            'status' => SecurityServices::SUCCESS_STATUS,
            'message' => 'Ich werde Ihnen direkt an die angegebene E-Mail-Adresse antworten'
        ]);
    }
}