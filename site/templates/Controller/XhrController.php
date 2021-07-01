<?php

namespace App\Controller;

use App\Core\AbstractController;
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
        if (isset($sanitizedData['error'])) {
            return json_encode($sanitizedData);
        }

    }
}