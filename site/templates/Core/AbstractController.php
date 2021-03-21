<?php

namespace App\Core;

use ProcessWire\ProcessWire;
use \Twig\Environment;
use \Twig\Loader\FilesystemLoader;

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
        $loader = new FilesystemLoader($this->wire->config('twigTemplates'));
        $this->twig = new Environment($loader, ['debug' => $this->wire->config('twigDebug')]);
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