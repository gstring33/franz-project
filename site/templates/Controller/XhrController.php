<?php

namespace App\Controller;

use App\Core\AbstractController;
use App\Services\PHPMailerServices;
use App\Services\SanitizerServices;
use ProcessWire\WireInput;

class XhrController extends AbstractController
{
    private $sanitizerServices;

    public function __construct()
    {
        $this->sanitizerServices = new SanitizerServices();
    }

    public function contact(WireInput $request)
    {
        if ($request->requestMethod() !== 'POST') {
            return json_encode([]);
        }

        $sanitizedData = $this->sanitizerServices->sanitizeContactData($request->post()->getArray());
        if (isset($sanitizedData['errors'])) {
            return json_encode([
                'status' => SanitizerServices::ERROR_STATUS,
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
            'status' => SanitizerServices::SUCCESS_STATUS,
            'message' => 'Ich werde Ihnen direkt an die angegebene E-Mail-Adresse antworten'
        ]);
    }
}