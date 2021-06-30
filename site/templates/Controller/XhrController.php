<?php

namespace App\Controller;

use App\Core\AbstractController;

class XhrController extends AbstractController
{
    public function contact()
    {
        return json_encode(['status' => 'succes' ]);
    }
}