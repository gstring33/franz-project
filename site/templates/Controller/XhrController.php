<?php

namespace App\Controller;

use App\Core\AbstractController;
use App\Services\PHPMailerServices;
use App\Services\SecurityServices;
use ProcessWire\WireInput;

class XhrController extends AbstractController
{
    private $sanitizerServices;

    public function __construct()
    {
        $this->securityServices = new SecurityServices();
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

        // Send Email if prod
        if ($this->isProd()) {
            $emailContent = $this->render('@email/contact.html.twig', [
                'contactMessage' => $sanitizedData['message'],
                'contactEmail' => $sanitizedData['email'],
                'contactName' => $sanitizedData['name']
            ]);

            $mailer = new PHPMailerServices();
            $mailer->send('martindhenu@yahoo.fr', 'Neuer Kontakt', $emailContent);
        }

        return json_encode([
            'status' => SecurityServices::SUCCESS_STATUS,
            'message' => 'Ich werde Ihnen direkt an die angegebene E-Mail-Adresse antworten'
        ]);
    }
}