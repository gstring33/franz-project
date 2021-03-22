<?php

namespace App\Core;

use ProcessWire\ProcessWire;
use \Twig\Environment;

class AbstractController
{
    /** @var ProcessWire|null $wire */
    private $wire;

    /** @var Environment $twig */
    private $twig;

    /**
     * AbstractController constructor.
     */
    public function __construct()
    {
        $this->wire = ProcessWire::getCurrentInstance();
        $this->twig = new TwigRenderer();
    }

    /**
     * @return Page
     */
    public function page(): Page
    {
        return $this->wire('page');
    }

    /**
     * @param string $view
     * @param array $params
     */
    public function render(string $view, array $params)
    {
        echo $this->twig->render($view, $params);
    }
}