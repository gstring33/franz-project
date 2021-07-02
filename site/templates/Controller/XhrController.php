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

        $sanitizedData = $this->securityServices->sanitizeContactData($request->post()->getArray());
        if (isset($sanitizedData['errors'])) {
            return json_encode([
                'status' => SecurityServices::ERROR_STATUS,
                'errors' => $sanitizedData['errors']
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