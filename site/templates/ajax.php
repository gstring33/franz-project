<?php

namespace ProcessWire;

use App\Controller\XhrController;

if ($config->ajax) {
    $xhrController = new XhrController();
    switch ($input->urlSegment(1)) {
        case 'contact':
            $response = $xhrController->contact();
            echo $response;
    }
}else {
    $session->redirect('/');
}