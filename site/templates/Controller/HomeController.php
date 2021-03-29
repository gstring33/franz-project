<?php

namespace App\Controller;

use App\Core\AbstractController;
use App\Services\SayHello;

class HomeController extends AbstractController
{
    /** @var SayHello */
    private $sayHello;

    /**
     * Home constructor.
     * @param SayHello $sayHello
     */
    public function __construct()
    {
        parent::__construct();
        $this->sayHello = new SayHello();
    }

    public function index()
    {
        $sayHello = $this->sayHello->sayHello();

        $this->render('@content/home.html.twig', ['message' => $sayHello . ' Martin']);
    }
}