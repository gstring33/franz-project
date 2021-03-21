<?php

namespace ProcessWire;

use App\Controller\HomeController;

switch ($page->url) {
    case '/' :
        $home = new HomeController();
        $home->index();

}

